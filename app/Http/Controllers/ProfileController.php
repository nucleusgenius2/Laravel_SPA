<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Services\ProfileService;
use App\Traits\StructuredResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    use StructuredResponse;

    protected ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }


    /**
     * Возвращает информацию для профиля юзера
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $dataArrayDTO = $this->service->getProfileData(user: request()->user());

        $this->status = 'success';
        $this->code = 200;
        $this->dataJson = $dataArrayDTO;

        return $this->responseJsonApi();
    }


    /**
     * Обновление данных пользователя
     * @param ProfileRequest $request
     * @return JsonResponse
     */
    public function update(ProfileRequest $request): JsonResponse
    {
        $data = $request->validated();

        $dataVoidDTO = $this->service->updateProfile(user: request()->user(), data: $data);

        if($dataVoidDTO->status){
            $this->text = 'Данные успешно обновлены';
            $this->status = 'success';
            $this->code = 200;
        } else {
            $this->text = $dataVoidDTO->error;
            $this->code = $dataVoidDTO->code;
        }

        return $this->responseJsonApi();
    }
}
