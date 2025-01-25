<?php


namespace App\Http\Controllers;

use App\Http\Requests\ModRequest;
use App\Http\Requests\PageRequest;
use App\Http\Requests\PostRequest;
use App\Http\Requests\SearchByNameRequest;
use App\Models\Mod;
use App\Services\HashFileGenerated;
use App\Traits\StructuredResponse;
use App\Traits\UploadFiles;
use App\Traits\UploadsImages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ModController extends HashFileGenerated
{
    use StructuredResponse, UploadsImages, UploadFiles;

    public int $perPageFrontend = 10;

    public function downlandMod(SearchByNameRequest $request)
    {
        $data = $request->validated();

        $headers = [
            'Content-Type' => 'application/zip',
        ];

        $file = public_path() . "/mods/".$data['name'] ;

        //стратегические иконки
        if ( $data ['name'] ==='icons1' ) {
            $file = public_path() . "/mods/targe_icons_1/Advanced strategic icons.nxt";
        }
        if ( $data ['name'] ==='icons2' ) {
            $file = public_path() . "/mods/targe_icons_2/Advanced strategic icons.nxt";
        }
        if ($data ['name'] ==='icons3' ) {
            $file = public_path() . "/mods/targe_icons_3/Advanced strategic icons.nxt";
        }

        return response()->download($file, $data ['name'], $headers);
    }


    public function index(PageRequest $request) : JsonResponse
    {
        $data = $request->validated();

        $mapsList = Mod::orderBy('mod_rate', 'desc')->paginate($this->perPageFrontend , ['*'], 'page', $data['page']);

        if (count($mapsList) > 0) {
            $this->status = 'success';
            $this->dataJson = $mapsList;
            $this->code = 200;
        } else {
            $this->text = 'Запрашиваемого мода не существует';
            $this->code = 404;
        }

        return $this->responseJsonApi();
    }


    public function store(ModRequest $request) : JsonResponse
    {
        $data = $request->validated();

        $imgUpload = $this->uploadImage($data['url_img'],'preview_mods');
        if ( $imgUpload['status'] =='success' ) {

            $fileUpload = $this->uploadFile($data['mod_archive'],'file|mimes:zip|max:204800', $data['name'],'mods');
            if ( $fileUpload['status'] =='success' ) {

                $hash = $this->getHash('mods', $fileUpload['url']);
                if (!$hash) {
                    $hash = [];
                }

                $response = Mod::create([
                    'url_img' => $imgUpload['img'],
                    'url_name' => $fileUpload['url'],
                    'name' => $data['name'],
                    'name_dir' => $data['name_dir'],
                    'description' => $data['description'],
                    'author' => request()->user()->name,
                    'author_id' => request()->user()->id,
                    'version' => $data['version'],
                    'type' => $data['type'],
                    'ch' => json_encode($hash),
                    'mod_rate' => 0,
                ]);

                if ($response) {
                    $this->code = 200;
                    $this->status = 'success';
                }
            }
            else{
                $this->text = $fileUpload['text'];
            }
        }
        else{
            $this->text = $imgUpload['text'];
        }

        return $this->responseJsonApi();
    }


    /**
     * removing mods
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    function destroy(int $id): JsonResponse
    {
        if( $id > 0) {
            $fileDataBase = Mod::where('id', $id)->first();

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
