<?php

namespace App\Services;

use App\DTO\DataArrayDTO;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    public function login(array $data): DataArrayDTO
    {
        $user = User::where('email', $data['email'])->first();

        if($user) {
            if (Hash::check($data['password'], $user->password)) {

                $token = $user->createToken('token', ['permission:user'])->plainTextToken;

                $userData = [
                    'token' => $token,
                    'user' => $user->email,
                ];

                return new DataArrayDTO(status: true, data: $userData);
            } else {
                return new DataArrayDTO(status: false, error: 'Пароль не совпадает', code: 400);
            }
        } else {
            return new DataArrayDTO(status: false, error: 'Пользователь не найден', code: 404);
        }
    }
}
