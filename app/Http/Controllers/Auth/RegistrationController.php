<?php

namespace App\Http\Controllers\Auth;

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

    public function registration(RegistrationRequest $request): JsonResponse
    {
        $data = $request->validated();

        $userData = $this->service->createUser($data);

        if($userData['status']) {
            $this->status = 'success';
            $this->code = 200;
            $this->dataJson = [
                'token' => $userData['token'],
                'user' => $userData['user']->email,
            ];
            $this->text = 'Регистрация прошла успешно';
        }
        else{
            $this->code = 500;
            $this->text = 'Ошибка при регистрации: ' .  $userData['error'];
        }


        return $this->responseJsonApi();
    }
}
