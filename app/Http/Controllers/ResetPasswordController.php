<?php

namespace App\Http\Controllers;

use App\Models\LimitResetPassword;
use App\Models\User;
use App\Rules\ValidEmail;
use App\Traits\ResponseController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResetPasswordController
{
    use ResponseController;

    /**
     * отправка на почту ссылки для восстановления пароля
     * @param Request $request
     * @return JsonResponse
     */
    public function resetEmailMessage(Request $request): JsonResponse
    {

        $validated = Validator::make($request->all(), [
            'email' => ['required', 'email', new ValidEmail],
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $ip = request()->ip();
            $ip2 = request()->getClientIp();

            isset($ip) && $ip !=='' ? $ipFinal = $ip :  $ipFinal = $ip2;


            $limit = LimitResetPassword::where('user_email', '=', $data['email'])->orWhere('user_ip', '=', $ipFinal )->orderBy('id', 'desc')->first();

            if ( $limit && $limit->created_at > Carbon::today()) {
                $this->text = 'Вы уже делали восстановление пароля сегодня';
            }
            else{
                LimitResetPassword::create([
                    'user_email' => $data['email'],
                    'user_ip' => $ipFinal
                ]);

                $status = Password::sendResetLink(
                    $request->only('email')
                );

                $this->status = 'success';
            }

        }

        return $this->responseJsonApi();
    }

    /**
     * установка нового пароля
     * @param Request $request
     * @return JsonResponse
     */
    public function reset(Request $request): JsonResponse
    {

        $validated = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                         //'password' => Hash::make($password)
                      //  'password' => bcrypt($password)
                        'password' => $password
                    ])->setRememberToken(Str::random(60));
                    $user->save();

                    $this->status = 'success';

                    event(new PasswordReset($user));
                }
            );
        }


        return $this->responseJsonApi();
    }
}



