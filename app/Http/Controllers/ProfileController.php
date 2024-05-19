<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\UserBalanceOperations;
use App\Traits\ResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class ProfileController
{
    use ResponseController;

    /**
     * get data from user profile
     * @return JsonResponse
     */
    public function profileInfo(): JsonResponse
    {
        $user = request()->user();

        $dataBalance = UserBalanceOperations::where('user_id','=', $user->id)->orderBy('updated_at', 'desc')->limit(5)->with(['userBalance'])->get();

        $userData = [
            'name' => $user->name,
            'email' => $user->email,
            'data_balance' => $dataBalance
        ];

        return response()->json($userData, 200);
    }

    /**
     * updating information in the user profile
     * @param Request $request
     * @return JsonResponse
     */
    public function profileUpdate(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:30',
            'email' => 'email|max:30|unique:users,email,' . request()->user()->id,
            'password' => 'required|string|max:30',
            'newPassword' => 'string|max:30'
        ]);

        if ($validated->fails()) {
            $this->text = $validated ->errors();
        } else {
            $data = $validated->valid();

            $id = request()->user()->id;

            $user = User::where('id', $id)->first();

            if ($user) {
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

                    $this->text = 'Данные успешно обновлены';
                    $this->status = 'success';
                    $this->code = 200;
                } else {
                    $this->text = 'Не верно указан пароль';
                }
            }
            else {
                $this->text = 'Пользователь не существует';
            }

        }

        return $this->responseJsonApi();
    }
}
