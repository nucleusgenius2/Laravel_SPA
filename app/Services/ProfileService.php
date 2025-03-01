<?php

namespace App\Services;

use App\DTO\DataArrayDTO;
use App\DTO\DataVoidDTO;
use App\Models\User;
use App\Models\UserBalanceOperations;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    public function getProfileData(User $user): DataArrayDTO
    {
        $dataBalance = UserBalanceOperations::where('user_id', '=', $user->id)->orderBy('updated_at', 'desc')->limit(5)->with(['userBalance'])->get();

        $userData = [
            'name' => $user->name,
            'email' => $user->email,
            'data_balance' => $dataBalance
        ];

        return new DataArrayDTO(status: true, data: $userData);
    }

    public function updateProfile(User $user, array $data): DataVoidDTO
    {
        if (Hash::check($data['password'], $user->password)) {

            if ($data['newPassword'] === 'none') {
                $updated = $user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                ]);
            } else {
                $updated = $user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['newPassword']
                ]);
            }

            if ($updated) {
                return new DataVoidDTO(status: true);
            } else {
                return new DataVoidDTO(status: false, error: 'Данные не обновлены', code: 500);
            }
        } else {
            return new DataVoidDTO(status: false, error: 'Пароль не верен', code: 400);
        }
    }
}
