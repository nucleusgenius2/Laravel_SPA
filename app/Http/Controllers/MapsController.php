<?php


namespace App\Http\Controllers;


use App\Actions\HashFileGenerated;
use App\Models\Map;
use App\Traits\ResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use ZipArchive;

class MapsController extends HashFileGenerated
{
    use ResponseController;


    /**
     * скачать карту, только авторезированным
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downlandMap(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|min:4|max:100|regex:/(^[A-Za-z0-9.-_(?!\S*\s\S*\s)]+$)+/',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $headers = [
                'Content-Type' => 'application/zip',
            ];

            $file = public_path() . "/maps/".$data['name'];

            return response()->download($file, $data['name'], $headers);

        }
    }

    /**
     * Получаем список карт
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Map $map)
    {
        $validated = Validator::make($request->all(), [
            'page' => 'required|integer|min:1',
            'name' => 'string|min:1|max:50|regex:/(^[A-Za-z0-9.-_(?!\S*\s\S*\s)]+$)+/', //(?!\S*\s\S*\s) разрешает 1 пробел, но запрещает 2
            'total_player_from' => 'integer|min:1',
            'total_player_to' => 'integer|min:1',
            'size' => 'string|min:4|max:50|regex:/(^[A-Za-z0-9]+$)+/',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            //ветка фильтра
            if ( isset($data['name']) || isset($data['total_player_from']) || isset($data['total_player_to']) || isset($data['size']) ){
                $query = $map->filterCustom($data);

                $mapsList = $query->orderBy('map_rate', 'desc')->paginate(15, ['*'], 'page', $data['page']);
            }
            else {
                $mapsList = Map::orderBy('map_rate', 'desc')->paginate(15, ['*'], 'page', $data['page']);
            }

            if (count( $mapsList) > 0) {
                $this->status = 'success';
                $this->json =  $mapsList;
            } else {
                $this->text = 'Запрашиваемой страницы не существует';
            }

        }

        return $this->responseJsonApi();
    }


    /**
     * проверить есть ли карта в базе данных
     */
    public function hasMap(string $name)
    {
        $validated = Validator::make(['name' => $name], [
            'name' => 'string|min:1|max:50|regex:/(^[A-Za-z0-9.-_(?!\S*\s\S*\s)]+$)+/',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $map = Map::where('name', '=', $data['name'])->orderBy('map_rate', 'desc')->first();

            if ($map) {
                $this->status = 'success';
                $this->json = $map ;
            } else {
                $this->text = 'Запрашиваемой страницы не существует';
            }

        }

        return $this->responseJsonApi();
    }



    /**
     * загружаем новую карту
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'name' => 'required|string|unique:maps|min:4|max:50|regex:/(^[a-z0-9-_. ]+$)+/',
            'map_size' => 'required|string|max:20|regex:/(^[a-z0-9-]+$)+/',
            'version' => 'required|integer|min:1',
            'total_player' => 'required|integer|min:1|max:16',
            'rate' => 'required|integer|min:0|max:1',
            'url_img' => 'required|image|mimes:png|max:10000',
            'map_archive' => 'required|file|mimes:zip|max:25600',
        ]);


        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            //upload img in dir
            $imageName = $data['name'] . '.' . $data['url_img']->extension();
            $data['url_img']->move(public_path('maps/preview'), $imageName);

            //upload zip archive in dir
            $archiveName = $data['name'] . '.' . $data['map_archive']->extension();
            $data['map_archive']->move(public_path('maps'), $archiveName);


            //логика генерации хеша файлов
            $hash = $this->getHash('maps', $archiveName);
            if (!$hash){
                $hash = [];
            }

            $response = Map::create([
                'url_img' => $imageName,
                'url_name' => $archiveName,
                'name' => $data['name'],
                'author' => request()->user()->name,
                'author_id' => request()->user()->id,
                'version' => $data['version'],
                'total_player' => $data['total_player'],
                'rate' => $data['rate'],
                'size' => $data['map_size'],
                'ch' => json_encode($hash),
                'map_rate' => 0,
            ]);

            if( $response ) {
                $this->status = 'success';
            }

        }

        return $this->responseJsonApi();
    }


    /**
     * загружаем новую карту через ПАРСЕР
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadParserMap(Request $request)
    {
        $validated = Validator::make($request->all(), [
         //   'name' => 'required|string|unique:maps|min:4|max:50|regex:/(^[A-Za-z0-9.-_(?!\S*\s\S*\s)]+$)+/',
            'name' => 'required|string|unique:maps|min:4|max:50',
            'map_size' => 'required|string|max:20|regex:/(^[A-Za-z0-9-]+$)+/',
            'version' => 'required|integer|min:1',
            'total_player' => 'required|integer|min:1|max:16',
            'rate' => 'required|integer|min:0|max:1',
            'url_img' => 'required|string|max:200|url',
            'map_archive' => 'required|string|max:200|url',
            'total_game' => 'required|integer|min:0',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            if ($data['total_game'] > 150 ) {

                $archiveName = $data['name'] . '.zip';
                $imageName = $data['name'] . '.png';
                file_put_contents(public_path('maps') . '/' . $archiveName, fopen($data['map_archive'], 'r'));
                file_put_contents(public_path('maps') . '/preview/' . $imageName, fopen($data['url_img'], 'r'));

                //логика генерации хеша файлов
                $hash = $this->getHash('maps', $archiveName);
                if (!$hash) {
                    $hash = [];
                }

                //рассчитываем рейтинг карты исходя из количества игр на карте
                $rate = 0;
                if ($data['total_game'] > 70000) {
                    $rate = 4;
                } else if ($data['total_game'] > 10000) {
                    $rate = 3;
                } else if ($data['total_game'] > 6000) {
                    $rate = 2;
                } else if ($data['total_game'] > 2000) {
                    $rate = 1;
                }

                $response = Map::create([
                    'url_img' => $imageName,
                    'url_name' => $archiveName,
                    'name' => $data['name'],
                    'author' => request()->user()->name,
                    'author_id' => request()->user()->id,
                    'version' => $data['version'],
                    'total_player' => $data['total_player'],
                    'rate' => $data['rate'],
                    'size' => $data['map_size'],
                    'ch' => json_encode($hash),
                    'map_rate' => $rate,
                ]);

                if ($response) {
                    $this->status = 'success';
                }

            }
            else{
                $this->text = 'колчисетво игр '.$data['total_game'];
            }

        }

        return $this->responseJsonApi();

    }


    /**
     * удаление карт
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    function destroy(int $id)
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $fileDataBase = Map::where('id',  $data['id'])->first();
            if ( $fileDataBase ){

                //удаление архива с картой
                $removeArchive = File::delete(public_path('/maps/'.$fileDataBase->url_name));
                if ( $removeArchive ){
                    //удаление записи из базы
                    $removeDataBase = Map::where('id', $data['id'])->delete();

                    //удаление картинки превьюшки
                    if ($removeDataBase ) {
                        $removePreview = File::delete(public_path('/maps/preview/'.$fileDataBase->url_img));

                        if ($removePreview ) {
                            $this->status = 'success';
                        }
                    }
                }

            }

        }

        return $this->responseJsonApi();
    }
}
