<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;

class LogoutController
{
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
}
