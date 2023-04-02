<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class UserController
{

    /**
     * @var array
     */
    public array $response = [
        'data' => [
            'status' => 'error',
            'text' => '',
        ],
        'code' => 402
    ];

    /**
     * check admin permission user
     * @param User $user
     * @return bool
     */
    private function isAdminPermission(User $user): bool
    {
        $user_right = Permission::where('id', '=', $user['id'])->get();

        if (count($user_right)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * show link for admin page
     * @param Request $request
     * @return JsonResponse
     */
    public function checkStatusUser(Request $request): JsonResponse
    {
        $user = request()->user();
        if ($user->tokenCan('permission:admin')) {
            $data = ['status' => 'success', 'permission' => 'admin'];
        } else {
            $data = ['status' => 'success', 'permission' => 'user'];
        }

        return response()->json($data, 200);
    }


    /**
     * logout
     * @return JsonResponse
     */
    public function logoutUser(): JsonResponse
    {
        $user = request()->user();
        $user->tokens()->delete();

        $data =  ['status' => 'success'];
        return response()->json($data, 200);
    }


    /**
     * registration user
     * @param Request $request
     * @return JsonResponse
     */
    public function registrationUser(Request $request): JsonResponse
    {
        $response = $this->response;

        $validated = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validated->fails()) {
            $response['data']['text'] = $validated ->errors();
        } else {
            $data = $validated->valid();

            $user = User::create($data);

            $token = $user->createToken('token', ['permission:user'])->plainTextToken;

            $response['data']['status'] = 'success';
            $response['data']['text'] = 'Регистрация прошла успешно';
            $response['data']['token'] = $token;
            $response['data']['user'] = $user->email;
            $response['code'] = 200;
        }
        return response()->json($response['data'], $response['code']);
    }


    /**
     * login user
     * @param Request $request
     * @return JsonResponse
     */
    public function loginUser(Request $request): JsonResponse
    {
        $response = $this->response;

        $validated = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validated->fails()) {
            $response['data']['text'] = $validated ->errors();
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

                    $response['data']['status'] = 'success';
                    $response['data']['text'] = 'Регистрация прошла успешно';
                    $response['data']['token'] = $token;
                    $response['data']['user'] = $user->email;
                    $response['code'] = 200;
                } else {
                    $response['data']['text'] = 'Пароль не совпадает';
                }
            } else {
                $response['data']['text'] = 'Email не найден';
            }
        }

        return response()->json($response['data'], $response['code']);

    }


}
