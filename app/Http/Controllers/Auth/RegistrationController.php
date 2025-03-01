<?php

namespace App\Http\Controllers\Auth;

use App\DTO\DataArrayDTO;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Rules\ReCaptcha;
use App\Services\UserService;
use App\Traits\StructuredResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationController
{
    use StructuredResponse;

    public UserService $service;

    function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Обычная регистрация юзеров
     * @param RegistrationRequest $request
     * @return JsonResponse
     */
    public function registration(RegistrationRequest $request): JsonResponse
    {
        $data = $request->validated();

        $dataArrayDTO = $this->service->createUser($data);

        if($dataArrayDTO->status) {
            $this->status = 'success';
            $this->code = 200;
            $this->dataJson = $dataArrayDTO->data;
        }
        else{
            $this->code = $dataArrayDTO->code;
            $this->text = $dataArrayDTO->error;
        }

        return $this->responseJsonApi();
    }
}
