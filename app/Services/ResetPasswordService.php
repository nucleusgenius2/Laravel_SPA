<?php

namespace App\Services;

use App\DTO\DataVoidDTO;
use App\Models\LimitResetPassword;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordService
{
    public function startResetPassword(string $email): DataVoidDTO
    {
        $limit = LimitResetPassword::where('user_email', '=', $email)->orderBy('id', 'desc')->first();

        if ($limit && $limit->created_at > Carbon::today()) {
            return new DataVoidDTO(status: false, error: 'Вы уже делали восстановление пароля сегодня', code: 400);
        } else {
            $limit = LimitResetPassword::create([
                'user_email' => $email,
            ]);
            if ($limit) {

                $status = Password::sendResetLink(
                    ['email' => $email]
                );
                return new DataVoidDTO(status: true);
            } else {
                return new DataVoidDTO(status: false, error: 'Ошибка при сохранении', code: 500);
            }
        }
    }

    public function ResetPassword(array $data): DataVoidDTO
    {
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password
                ])->setRememberToken(Str::random(60));

                if ($user->save()) {
                    event(new PasswordReset($user));

                    return new DataVoidDTO(status: true);
                } else {
                    return new DataVoidDTO(status: false, error: 'Ошибка сохранения данных', code: 500);
                }
            }
        );

        if($status === 'passwords.token'){
            return new DataVoidDTO(status: true);
        }
        else{
            return new DataVoidDTO(status: false, error: 'Ошибка сохранения данных', code: 400 );
        }
    }
}
