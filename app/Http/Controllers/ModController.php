<?php


namespace App\Http\Controllers;



use App\Models\Mod;
use App\Services\HashFileGenerated;
use App\Traits\ResponseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ModController extends HashFileGenerated
{
    use ResponseController;


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

            $imageName = $data['name'] . '.' . $data['url_img']->extension();
            $data['url_img']->move(public_path('preview_mods'), $imageName);

            $archiveName = str_replace(" ", "", $data['name']) . '.' . $data['mod_archive']->extension();

            $data['mod_archive']->move(public_path('mods'), $archiveName);

            $hash = $this->getHash('mods', $archiveName);
            if (!$hash){
                $hash = [];
            }

            $response = Mod::create([
                'url_img' => $imageName,
                'url_name' => $archiveName,
                'name' => $data['name'],
                'name_dir' =>  $data['name_dir'],
                'description' => $data['description'],
                'author' => request()->user()->name,
                'author_id' => request()->user()->id,
                'version' => $data['version'],
                'type' => $data['type'],
                'ch' =>  json_encode($hash),
                'mod_rate' => 0,
            ]);

            if( $response ) {
                $this->code = 200;
                $this->status = 'success';
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

                $removeArchive = File::delete(public_path('/mods/'.$fileDataBase->url_name));
                if ( $removeArchive ){

                    $removeDataBase = Mod::where('id', $data['id'])->delete();

                    if ($removeDataBase ) {
                        $removePreview = File::delete(public_path('/preview_mods/'.$fileDataBase->url_img));

                        if ($removePreview ) {
                            $this->status = 'success';
                            $this->code = 200;
                        }
                    }
                }

            }

        }

        return $this->responseJsonApi();
    }

}
