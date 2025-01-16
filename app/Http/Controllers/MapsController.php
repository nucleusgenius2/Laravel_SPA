<?php


namespace App\Http\Controllers;



use App\Models\Map;
use App\Services\HashFileGenerated;
use App\Traits\StructuredResponse;
use App\Traits\UploadFiles;
use App\Traits\UploadsImages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use ZipArchive;

class MapsController extends HashFileGenerated
{
    use StructuredResponse, UploadsImages, UploadFiles;

    public int $perPageFrontend = 15;
    /**
     * download the map
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
     * We get a list of maps
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Map $map): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'page' => 'required|integer|min:1',
            'name' => 'string|min:1|max:50|regex:/(^[A-Za-z0-9.-_(?!\S*\s\S*\s)]+$)+/',
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

                $mapsList = $query->orderBy('map_rate', 'desc')->paginate($this->perPageFrontend , ['*'], 'page', $data['page']);
            }
            else {
                $mapsList = Map::orderBy('map_rate', 'desc')->paginate($this->perPageFrontend, ['*'], 'page', $data['page']);
            }

            if (count( $mapsList) > 0) {
                $this->status = 'success';
                $this->code = 200;
                $this->dataJson =  $mapsList;
            } else {
                $this->text = 'Запрашиваемой страницы не существует';
            }

        }

        return $this->responseJsonApi();
    }


    /**
     * checking if the card is in the database
     * @param string $name
     * @return JsonResponse
     */
    public function hasMap(string $name): JsonResponse
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
                $this->code = 200;
                $this->dataJson = $map ;
            } else {
                $this->text = 'Запрашиваемой страницы не существует';
            }
        }

        return $this->responseJsonApi();
    }



    /**
     * uploading a new map
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) : JsonResponse
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

            $imgUpload = $this->uploadImage($data['url_img'],'maps/preview');
            if ( $imgUpload['status'] =='success' ) {

                $fileUpload = $this->uploadFile($data['map_archive'], 'file|mimes:zip|max:25600', $data['name'], 'maps');
                if ($fileUpload['status'] == 'success') {

                    $hash = $this->getHash('maps', $fileUpload['url']);
                    if (!$hash) {
                        $hash = [];
                    }

                    $response = Map::create([
                        'url_img' => $imgUpload['img'],
                        'url_name' => $fileUpload['url'],
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

                    if ($response) {
                        $this->status = 'success';
                        $this->code = 200;
                    }
                }
            }

        }

        return $this->responseJsonApi();
    }



    /**
     * deleting maps
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

                $removeArchive = File::delete(public_path($fileDataBase->url_name));
                if ( $removeArchive ){
                    $fileDataBase->delete();

                    $removePreview = File::delete(public_path($fileDataBase->url_img));
                    if ($removePreview ) {
                        $this->status = 'success';
                        $this->code = 200;
                    }
                }

            }

        }

        return $this->responseJsonApi();
    }
}
