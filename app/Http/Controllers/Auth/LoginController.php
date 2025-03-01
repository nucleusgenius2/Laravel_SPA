<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\LoginService;
use App\Traits\StructuredResponse;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    use StructuredResponse;

    protected LoginService $service;

    public function __construct(LoginService $service)
    {
        $this->service = $service;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $dataArrayDTO = $this->service->login(data: $data);

        if ($dataArrayDTO->status) {
            $this->status = 'success';
            $this->code = 200;
            $this->dataJson = $dataArrayDTO->data;
        } else {
            $this->code = $dataArrayDTO->code;
            $this->text = $dataArrayDTO->error;
        }

        return $this->responseJsonApi();
    }

}
