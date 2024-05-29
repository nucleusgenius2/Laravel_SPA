<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\UserLogin;
use App\Jobs\SendMail;
use App\Models\Post;
use App\Models\UserBalance;
use App\Traits\ResponseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class UserController
{
    use ResponseController;

    /**
     * check admin permission user
     * @param User $user
     * @return bool
     */
    public function isAdminPermission(User $user): bool
    {
        if ($user->status === 2) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * show link for admin page
     * @param Request $request
     * @return JsonResponse
     */
    public function checkStatusUser(Request $request): JsonResponse
    {
        $user = request()->user();

        if ($user->tokenCan('permission:admin')) {
            $data = ['status' => 'success', 'permission' => 'admin'];
        } else {
            $data = ['status' => 'success', 'permission' => 'user'];
        }

        return response()->json($data, 200);
    }


    /**
     * logout
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = request()->user();

        $user->tokens()->delete();

        $data = ['status' => 'success'];

        return response()->json($data, 200);
    }


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

    /**
     * get user list
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $validated = Validator::make(['page' => $request->page], [
            'page' => 'integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $postUser = User::orderBy('id', 'desc')->paginate(10, ['*'], 'page', $data['page']);

            if (count($postUser) > 0) {
                $this->status = 'success';
                $this->code = 200;
                $this->json = $postUser;
            } else {
                $this->text = 'таблица юзеров пуста';
            }
        }

        return $this->responseJsonApi();
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $validated = Validator::make(['id' => $id], [
            'id' => 'integer|min:1',
        ]);

        if ($validated->fails()) {
            $this->text = $validated->errors();
        } else {
            $data = $validated->valid();

            $contentUserSingle = User::where('id', '=', $data['id'])->get();

            if (count($contentUserSingle) > 0) {
                $this->status = 'success';
                $this->code = 200;
                $this->json = $contentUserSingle;
            } else {
                $this->text = 'юзера не существует';
            }
        }

        return $this->responseJsonApi();
    }
}
