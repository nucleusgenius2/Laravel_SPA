<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function createUser(array $data): array
    {
        try {
            $user = User::create($data);

            $token = $user->createToken('token', ['permission:user'])->plainTextToken;

            event(new Registered($user));

            return [
                'status' => true,
                'token' => $token,
                'user' => $user
            ];
        }
        catch (\Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
