<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\UserLogin;
use App\Jobs\SendMail;
use App\Models\Post;
use App\Models\UserBalance;
use App\Rules\ReCaptcha;
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
     * @return JsonResponse
     */
    public function checkStatusUser(): JsonResponse
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
