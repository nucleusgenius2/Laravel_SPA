<?php


namespace App\Http\Controllers;



use App\Http\Requests\ModsRequest;
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


    /**
     * download the mod, only authorized
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downlandMod(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|min:4|max:50|regex:/(^[A-Za-z0-9-_.]+$)+/',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $mapName = $validated->valid();

            $headers = [
                'Content-Type' => 'application/zip',
            ];

            $file = public_path() . "/mods/".$mapName['name'] ;

            //стратегические иконки
            if ( $mapName['name'] ==='icons1' ) {
                $file = public_path() . "/mods/targe_icons_1/Advanced strategic icons.nxt";
            }
            if ( $mapName['name'] ==='icons2' ) {
                $file = public_path() . "/mods/targe_icons_2/Advanced strategic icons.nxt";
            }
            if ( $mapName['name'] ==='icons3' ) {
                $file = public_path() . "/mods/targe_icons_3/Advanced strategic icons.nxt";
            }

            return response()->download($file, $mapName['name'], $headers);
        }
    }

    /**
     * We get a list of mods
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'page' => 'required|integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $mapsList = Mod::orderBy('mod_rate', 'desc')->paginate(10, ['*'], 'page', $data['page']);

            if (count( $mapsList) > 0) {
                $this->status = 'success';
                $this->json =  $mapsList;
                $this->code = 200;
            } else {
                $this->text = 'Запрашиваемой страницы не существует';
            }

        }

        return $this->responseJsonApi();
    }

    /**
     * downloading a new mod
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|unique:maps|min:4|max:50|regex:/(^[A-Za-z0-9-_. ]+$)+/',
            'name_dir' => 'required|string|unique:mods|min:4|max:50|regex:/(^[A-Za-z0-9-_. ]+$)+/',
            'description' => 'required|string|max:255',
            'version' => 'required|integer|min:1',
            'type' => 'required|integer|min:0|max:1',
            'url_img' => 'required|image|mimes:png,jpg,jpeg|max:10000',
            'mod_archive' => 'required|file|mimes:zip|max:204800',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

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

        $validated = Validator::make(['id' => $id], [
            'id' => 'integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $fileDataBase = Mod::where('id',  $data['id'])->first();
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
