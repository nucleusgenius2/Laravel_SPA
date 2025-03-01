<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\PageRequest;
use App\Services\UserService;

use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public int $perPageFrontend = 10;

    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * Получить список юзеров
     * @param PageRequest $request
     * @return JsonResponse
     */
    public function index(PageRequest $request): JsonResponse
    {
        $data = $request->validated();

        $dataObjectDTO = $this->service->getUsers($data, perPage: $this->perPageFrontend);

        $this->status = 'success';
        $this->code = 200;
        $this->dataJson = $dataObjectDTO->data;

        return $this->responseJsonApi();
    }


    /**
     * Данные о конкретном юзере
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $dataObjectDTO = $this->service->getUser(id: $id);

        if ($dataObjectDTO->status) {
            $this->status = 'success';
            $this->code = 200;
            $this->dataJson = $dataObjectDTO->data;
        } else {
            $this->code = $dataObjectDTO->code;
            $this->text = $dataObjectDTO->error;
        }

        return $this->responseJsonApi();
    }


    /**
     * Проверка роли юзера
     * @return JsonResponse
     */
    public function checkStatusUser(): JsonResponse
    {
        if (Gate::allows('is_admin')) {
            $data = ['status' => 'success', 'permission' => 'admin'];
        } else {
            $data = ['status' => 'success', 'permission' => 'user'];
        }

        return response()->json($data, 200);
    }
}
