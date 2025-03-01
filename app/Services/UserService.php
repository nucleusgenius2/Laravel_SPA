<?php

namespace App\Services;

use App\DTO\DataArrayDTO;
use App\DTO\DataObjectDTO;
use App\DTO\DataVoidDTO;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function createUser(array $data): DataArrayDTO
    {
        try {
            $user = User::create($data);

            $token = $user->createToken('token', ['permission:user'])->plainTextToken;

            event(new Registered($user));

            $data = [
                'token' => $token,
                'user' => $user->email
            ];

            return new DataArrayDTO(status: true, data: $data);
        } catch (\Exception $e) {
            return new DataArrayDTO(status: false, error: $e->getMessage(), code: 500);
        }
    }

    public function getUsers(array $data, int $perPage): DataObjectDTO
    {
        $postUser = User::orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $data['page']);

        return new DataObjectDTO(status: true, data: $postUser);
    }

    public function getUser(int $id): DataObjectDTO
    {
        if ($id < 1) {
            return new DataObjectDTO(status: false, error: 'id не может быть меньше 1', code: 422);
        }

        $user = User::where('id', '=', $id)->firts();
        if ($user) {
            return new DataObjectDTO(status: true, data: $user);
        } else {
            return new DataObjectDTO(status: false, error: 'Пользователь не найден', code: 404);
        }
    }
}
