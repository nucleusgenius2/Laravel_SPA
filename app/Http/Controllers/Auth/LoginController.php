<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\UserController;
use App\Models\User;
use App\Traits\StructuredResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends UserController
{
    use StructuredResponse;

    /**
     * @OA\Post(
     *  path="/api/login",
     *  summary="Login user",
     *  description="Login user by email and password. If successful, you will receive a token for this user",
     *  operationId="authLogin",
     *  tags={"auth"},
     *  @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", maxLength=30, example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", maxLength=30, minLength=6, example="PassWord12345"),
     *    ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Register Successfully",
     *    @OA\JsonContent(
     *       @OA\Property(property="text", type="string", example="Вход успешен"),
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="json", type="array", @OA\Items(
     *           @OA\Property(
     *               property="token",
     *               type="string"
     *           ),
     *           @OA\Property(
     *               property="user",
     *               type="string",
     *               example="user1@mail.com"
     *          ),
     *       )),
     *    )
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="Unprocessable Entity",
     *      @OA\JsonContent(
     *         @OA\Property(property="text", type="string", example="Email не найден"),
     *         @OA\Property(property="status", type="string", example="error"),
     *         @OA\Property(property="json", type="null" )
     *       )
     *  )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validated->fails()) {
            $this->text = $validated ->errors();
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

                    $dataUser = [
                        'token' => $token,
                        'user' => $user->email,
                    ];

                    $this->status = 'success';
                    $this->code = 200;
                    $this->json = $dataUser;
                    $this->text = 'Вход успешен';

                    //UserLogin::dispatch($user);
                } else {
                    $this->text = 'Пароль не совпадает';
                }
            } else {
                $this->text = 'Email не найден';
            }
        }

        return $this->responseJsonApi();
    }

}
