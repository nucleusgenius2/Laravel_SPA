<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class ProfileController
{

    /**
     * get data from user profile
     * @return JsonResponse
     */
    public function profileInfo(): JsonResponse
    {
        $user = request()->user();

        return response()->json($user, 200);
    }

    /**
     * updating information in the user profile
     * @param Request $request
     * @return JsonResponse
     */
    public function profileUpdate(Request $request): JsonResponse
    {
        $response = [
            'data' => [
                'status' => 'error',
                'text' => '',
            ],
            'code' => 402
        ];

        $validated = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'email|unique:users,email,' . request()->user()->id,
            'password' => 'required|string',
            'newPassword' => 'string'
        ]);

        if ($validated->fails()) {
            $response['data']['text'] = $validated ->errors();
        } else {
            $data = $validated->valid();

            $id = request()->user()->id;

            $user = User::where('id', $id)->first();

            //check password user
            if (Hash::check($data['password'], $user->password)) {

                if ($request->newPassword === 'none') {
                    $user->update([
                        'name' => $data['name'],
                        'email' => $data['email'],
                    ]);
                } else {
                    $user->update([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'password' => $data['newPassword']
                    ]);
                }

                $response['data']['status'] = 'success';
                $response['data']['text'] = 'Данные успешно обновлены';
                $response['code'] = 200;
            } else {
                $response['data']['text'] = 'Не верно указан пароль';
            }

        }
        return response()->json($response['data'], $response['code']);
    }


}
