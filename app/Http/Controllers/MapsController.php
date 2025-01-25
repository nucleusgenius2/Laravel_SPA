<?php


namespace App\Http\Controllers;



use App\Http\Requests\MapRequest;
use App\Http\Requests\MapSearchRequest;
use App\Http\Requests\SearchByNameRequest;
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
    public function downlandMap(SearchByNameRequest $request)
    {
        $data =  $request->validated();

        $headers = [
            'Content-Type' => 'application/zip',
        ];

        $file = public_path() . "/maps/".$data['name'];

        return response()->download($file, $data['name'], $headers);
    }


    public function index(MapSearchRequest $request, Map $map): JsonResponse
    {
        $data = $request->validated();

        //ветка фильтра
        if ( isset($data['name']) || isset($data['total_player_from']) || isset($data['total_player_to']) || isset($data['size']) ){
            $query = $map->filterCustom($data);

            $mapsList = $query->orderBy('map_rate', 'desc')->paginate($this->perPageFrontend , ['*'], 'page', $data['page']);
        }
        else {
            $mapsList = Map::orderBy('map_rate', 'desc')->paginate($this->perPageFrontend, ['*'], 'page', $data['page']);
        }

        if (count($mapsList) > 0) {
            $this->status = 'success';
            $this->code = 200;
            $this->dataJson =  $mapsList;
        } else {
            $this->text = 'Запрашиваемой страницы не существует';
            $this->code = 404;
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
                $this->text = 'Запрашиваемой карты не существует';
                $this->code = 404;
            }
        }

        return $this->responseJsonApi();
    }


    public function store(MapRequest $request) : JsonResponse
    {
        $data = $request->validated();

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

        return $this->responseJsonApi();
    }



    /**
     * deleting maps
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    function destroy(int $id)
    {
        if ( $id > 0){

            $fileDataBase = Map::where('id',  $id)->first();
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
