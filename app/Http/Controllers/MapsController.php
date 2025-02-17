<?php


namespace App\Http\Controllers;



use App\Http\Requests\MapRequest;
use App\Http\Requests\MapSearchRequest;
use App\Http\Requests\SearchByNameRequest;
use App\Models\Map;
use App\Services\MapsService;
use Illuminate\Http\JsonResponse;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class MapsController extends Controller
{
    public int $perPageFrontend = 15;

    protected MapsService $service;

    public function __construct(MapsService $service)
    {
        $this->service = $service;
    }


    /**
     * Загрузка карт на сервер
     * @param SearchByNameRequest $request
     * @return BinaryFileResponse
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


    /**
     * Получить список карт
     * @param MapSearchRequest $request
     * @param Map $map
     * @return JsonResponse
     */
    public function index(MapSearchRequest $request, Map $map): JsonResponse
    {
        $data = $request->validated();

        $dataObjectDTO = $this->service->getMaps(data: $data, map: $map, perPage: $this->perPageFrontend);

        if ($dataObjectDTO->status) {
            $this->status = 'success';
            $this->code = 200;
            $this->dataJson = $dataObjectDTO->data;
        } else {
            $this->code = $dataObjectDTO->code ?? 400;
            $this->text = $dataObjectDTO->error;
        }

        return $this->responseJsonApi();
    }


    /**
     * Проверка есть ли карта в базе
     * @param string $name
     * @return JsonResponse
     */
    public function hasMap(string $name): JsonResponse
    {
        $dataVoidDTO = $this->service->hasMap(mapName: $name);

        if ($dataVoidDTO->status) {
            $this->status = 'success';
            $this->code = 200;
        } else {
            $this->code = $dataVoidDTO->code ?? 400;
            $this->text = $dataVoidDTO->error;
        }

        return $this->responseJsonApi();
    }


    public function store(MapRequest $request): JsonResponse
    {
        $data = $request->validated();

        $dataVoidDTO = $this->service->createMaps(data: $data, user: request()->user());

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



    function destroy(int $id)
    {
        if ( $id > 0){

            $dataVoidDTO = $this->service->deleteMaps(id: $id);

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
