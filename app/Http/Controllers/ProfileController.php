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

    /**
     * @OA\Post(
     *  path="/api/profile",
     *  summary="Сhange user data",
     *  security={{"bearer_token":{}}},
     *  description="Сhange user data by name, email, password",
     *  operationId="userUpdate",
     *  tags={"user"},
     *  @OA\RequestBody(
     *    required=true,
     *    description="Transfer the changed user data. The newPassword is sent only if the password has changed",
     *    @OA\JsonContent(
     *       required={"name","email","password","newPassword"},
     *       @OA\Property(property="name", type="string", maxLength=30, example="user1"),
     *       @OA\Property(property="email", type="string", format="email", maxLength=30, example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", maxLength=30, minLength=6, example="PassWord12345"),
     *       @OA\Property(property="newPassword", type="string", format="password"),
     *    ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Register Successfully",
     *    @OA\JsonContent(
     *       @OA\Property(property="text", type="string", example="Данные успешно обновлены"),
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="json", type="array", @OA\Items() ),
     *    )
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity",
     *      @OA\JsonContent(
     *         @OA\Property(property="text", type="string", example="Пользователь не существует"),
     *         @OA\Property(property="status", type="string", example="error"),
     *         @OA\Property(property="json", type="array", @OA\Items() )
     *       )
     *  )
     * )
     */
    public function update(Request $request): JsonResponse
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
