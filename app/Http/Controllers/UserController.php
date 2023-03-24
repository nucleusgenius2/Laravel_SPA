<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;


class UserController
{

    /**
     * chek admin permission user
     * @param User $user
     * @return bool
     */
    private function isAdminPermission(User $user): bool
    {

        $user_right = Permission::where('id', '=', $user['id'])->get();
        if (count($user_right)) {
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

        $data =  ['status' => 'success'];
        return response()->json($data, 200);
    }


    /**
     * registration user
     * @param Request $request
     * @return JsonResponse
     */
    public function registrationUser(Request $request): JsonResponse
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::create(request(['name', 'email', 'password']));
        $token = $user->createToken('token', ['permission:user'])->plainTextToken;

        $data = ['status' => 'success', 'user' => $user->email, 'token' => $token];
        return response()->json($data, 200);

    }


    /**
     * login user
     * @param Request $request
     * @return JsonResponse
     */
    public function loginUser(Request $request): JsonResponse
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        $user = User::where('email', $request->email)->first();

        //permission user
        if ($this->isAdminPermission($user)) {
            $token = $user->createToken('token', ['permission:admin'])->plainTextToken;
        } else {
            $token = $user->createToken('token', ['permission:user'])->plainTextToken;
        }

        $data = ['status' => 'success', 'user' => $user->email, 'token' => $token];
        return response()->json($data, 200);

    }


}
