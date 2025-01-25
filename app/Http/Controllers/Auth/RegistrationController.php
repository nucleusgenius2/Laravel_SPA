<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Rules\ReCaptcha;
use App\Traits\StructuredResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationController
{
    use StructuredResponse;


    public function registration(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:16|regex:/(^[A-Za-z0-9-_]+$)+/',
            'email' => 'required|email|unique:users|max:30',
            'password' => 'required|string|confirmed|min:6|max:30',
            'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);

        if ($validated->fails()) {
            $this->text = $validated ->errors();
        } else {
            $data = $validated->valid();

            $user = User::create($data);

            $token = $user->createToken('token', ['permission:user'])->plainTextToken;

            $dataUser = [
                'token' => $token,
                'user' => $user->email,
            ];

            $this->status = 'success';
            $this->code = 200;
            $this->json = $dataUser;
            $this->text = 'Регистрация прошла успешно';

            event(new Registered($user));
        }

        return $this->responseJsonApi();
    }
}
