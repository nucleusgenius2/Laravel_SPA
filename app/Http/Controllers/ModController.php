<?php


namespace App\Http\Controllers;

use App\Http\Requests\ModRequest;
use App\Http\Requests\PageRequest;
use App\Http\Requests\SearchByNameRequest;
use App\Models\Mod;
use App\Services\ModService;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class ModController extends Controller
{

    protected ModService $service;

    public function __construct(ModService $service)
    {
        $this->service = $service;
    }

    public int $perPageFrontend = 10;

    /**
     * Скачивание модов
     * @param SearchByNameRequest $request
     * @return BinaryFileResponse
     */
    public function downlandMod(SearchByNameRequest $request)
    {
        $data = $request->validated();

        $headers = [
            'Content-Type' => 'application/zip',
        ];

        $file = public_path() . "/mods/".$data['name'] ;

        //стратегические иконки
        if ( $data ['name'] ==='icons1' ) {
            $file = public_path() . "/mods/target_icons_1/Advanced strategic icons.nxt";
        }
        if ( $data ['name'] ==='icons2' ) {
            $file = public_path() . "/mods/target_icons_2/Advanced strategic icons.nxt";
        }
        if ($data ['name'] ==='icons3' ) {
            $file = public_path() . "/mods/target_icons_3/Advanced strategic icons.nxt";
        }

        return response()->download($file, $data ['name'], $headers);
    }


    /**
     * Получение списка модов
     * @param PageRequest $request
     * @return JsonResponse
     */
    public function index(PageRequest $request) : JsonResponse
    {
        $data = $request->validated();

        $dataObjectDTO = $this->service->getMods(data: $data, perPage: $this->perPageFrontend);

        if ($dataObjectDTO->status) {
            $this->status = 'success';
            $this->code = 200;
            $this->dataJson = $dataObjectDTO->data;
        } else {
            $this->code = $dataObjectDTO->code ?? 400;
        }

        return $this->responseJsonApi();
    }


    /**
     * Добавление модов
     * @param ModRequest $request
     * @return JsonResponse
     */
    public function store(ModRequest $request) : JsonResponse
    {
        $data = $request->validated();

        $dataVoidDTO = $this->service->createMods(data: $data, user: request()->user());

        if ($dataVoidDTO->status) {
            $this->status = 'success';
            $this->code = 200;
        }
        else{
            $this->code = $dataVoidDTO->code ?? 400;
            $this->text = $dataVoidDTO->error;
        }

        return $this->responseJsonApi();
    }


    /**
     * Удаление модов
     * @param int $id
     * @return JsonResponse
     */
    function destroy(int $id): JsonResponse
    {
        if ( $id > 0){

            $dataVoidDTO = $this->service->deleteMods(id: $id);

            if ($dataVoidDTO->status) {
                $this->status = 'success';
                $this->code = 200;
            }
            else{
                $this->code = $dataVoidDTO->code ?? 400;
                $this->text = $dataVoidDTO->error;
            }
        }

        return $this->responseJsonApi();
    }

}
