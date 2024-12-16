<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Rules\ReCaptcha;
use App\Traits\StructuredResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationController
{
    use StructuredResponse;

    /**
     * @OA\Post(
     *  path="/api/registration",
     *  summary="Registration user",
     *  description="Registration user by name, email, password",
     *  operationId="authRegistration",
     *  tags={"auth"},
     *  @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"name","email","password","password_confirmation"},
     *       @OA\Property(property="name", type="string", maxLength=30, example="user1"),
     *       @OA\Property(property="email", type="string", format="email", maxLength=30, example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", maxLength=30, minLength=6, example="PassWord12345"),
     *       @OA\Property(property="password_confirmation", type="string", format="password"),
     *    ),
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Register Successfully",
     *    @OA\JsonContent(
     *       @OA\Property(property="text", type="string", example="Регистрация прошла успешно"),
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
     *         @OA\Property(property="text", type="null"),
     *         @OA\Property(property="status", type="string", example="error"),
     *         @OA\Property(property="json", type="null")
     *       )
     *  )
     * )
     */
    public function registration(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:16|regex:/(^[A-Za-z0-9-_]+$)+/',
            'email' => 'required|email|unique:users|max:30',
            'password' => 'required|string|confirmed|min:6|max:30',
            'g-recaptcha-response' => ['required', new ReCaptcha]
        ]);

        if ($validated->fails()) {
            $this->text = $validated ->errors();
        } else {
            $data = $validated->valid();

            $user = User::create($data);

            $token = $user->createToken('token', ['permission:user'])->plainTextToken;

            $dataUser = [
                'token' => $token,
                'user' => $user->email,
            ];

            $this->status = 'success';
            $this->code = 200;
            $this->json = $dataUser;
            $this->text = 'Регистрация прошла успешно';

            event(new Registered($user));
        }

        return $this->responseJsonApi();
    }
}
