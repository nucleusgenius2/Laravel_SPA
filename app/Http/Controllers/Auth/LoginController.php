<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\UserController;
use App\Models\User;
use App\Traits\StructuredResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends UserController
{
    use StructuredResponse;

    public function login(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validated->fails()) {
            $this->text = $validated ->errors();
        } else {
            $user = User::where('email', $request->email)->first();

            if($user) {
                if (Hash::check($request->password, $user->password)) {
                    /* permission user */
                    if ($this->isAdminPermission($user)) {
                        $token = $user->createToken('token', ['permission:admin'])->plainTextToken;
                    } else {
                        $token = $user->createToken('token', ['permission:user'])->plainTextToken;
                    }

                    $dataUser = [
                        'token' => $token,
                        'user' => $user->email,
                    ];

                    $this->status = 'success';
                    $this->code = 200;
                    $this->dataJson = $dataUser;
                    $this->text = 'Вход успешен';

                    //UserLogin::dispatch($user);
                } else {
                    $this->text = 'Пароль не совпадает';
                }
            } else {
                $this->text = 'Email не найден';
            }
        }

        return $this->responseJsonApi();
    }

}
