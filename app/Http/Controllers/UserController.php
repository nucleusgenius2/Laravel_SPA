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
use App\Models\UserRights;
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
    public function logoutUser(): JsonResponse
    {
        $user = request()->user();

        $user->tokens()->delete();

        $data = ['status' => 'success'];

        return response()->json($data, 200);
    }


    /**
     * registration user
     * @param Request $request
     * @return JsonResponse
     */
    public function registrationUser(Request $request): JsonResponse
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

            $balance = UserBalance::create(['user_id' => $user->id, 'balance' => 0]);

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
     * login user
     * @param Request $request
     * @return JsonResponse
     */
    public function loginUser(Request $request): JsonResponse
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
                    $this->text = 'Регистрация прошла успешно';

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
    public function getUserList(Request $request): JsonResponse
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
    public function getUserSingle(int $id): JsonResponse
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
