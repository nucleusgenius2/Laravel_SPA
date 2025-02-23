<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\StructuredResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoginController extends UserController
{
    use StructuredResponse;

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if($user) {
            if (Hash::check($request->password, $user->password)) {

                $token = $user->createToken('token', ['permission:user'])->plainTextToken;

                $dataUser = [
                    'token' => $token,
                    'user' => $user->email,
                ];

                $this->status = 'success';
                $this->code = 200;
                $this->dataJson = $dataUser;
                $this->text = 'Вход успешен';
            } else {
                $this->text = 'Пароль не совпадает';
                $this->code = 400;
            }
        } else {
            $this->text = 'Email не найден';
            $this->code = 400;
        }

        return $this->responseJsonApi();
    }

}
