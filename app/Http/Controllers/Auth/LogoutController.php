<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class LogoutController extends Controller
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
