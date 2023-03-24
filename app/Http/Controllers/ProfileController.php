<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class ProfileController
{

    /**
     * get data from user profile
     * @return JsonResponse
     */
    public function profileInfo(): JsonResponse
    {
        $user = request()->user();
        return response()->json($user, 200);
    }

    /**
     * updating information in the user profile
     * @param Request $request
     * @return JsonResponse
     */
    public function profileUpdate(Request $request): JsonResponse
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'newPassword' => 'string'
        ]);

        $id = request()->user()->id;
        $user = User::where('id', $id)->first();

        //check password user
        if (Hash::check($request->password, $user->password)) {

            if ($request->newPassword == 'none') {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                ]);
            } else {
                $request->validate([
                    'newPassword' => 'string|min:6'
                ]);

                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->newPassword)
                ]);

            }
            $data= ["status" => 'success'];
            return response()->json($data, 200);
        } else {
            $data = ["status" => 'error'];
            return response()->json($data, 500);
        }


    }


}
