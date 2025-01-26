<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\UserBalanceOperations;
use App\Traits\StructuredResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    use StructuredResponse;

    /**
     * get data from user profile
     * @return JsonResponse
     */
    public function index(): JsonResponse
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


    public function update(ProfileRequest $request): JsonResponse
    {
        $data = $request -> validated();

        $user = request()->user();

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
            $this->code = 401;
        }

        return $this->responseJsonApi();
    }
}
