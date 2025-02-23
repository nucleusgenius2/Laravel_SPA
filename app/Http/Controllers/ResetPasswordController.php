<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Http\Requests\ResetEmailRequest;
use App\Services\ResetPasswordService;
use App\Traits\StructuredResponse;
use Illuminate\Http\JsonResponse;

class ResetPasswordController extends Controller
{
    use StructuredResponse;

    protected ResetPasswordService $service;

    public function __construct(ResetPasswordService $service)
    {
        $this->service = $service;
    }

    public function resetEmailMessage(EmailRequest $request): JsonResponse
    {
        $data = $request->validated();

        $dataVoidDTO = $this->service->startResetPassword(email: $data['email']);

        if($dataVoidDTO->status){
            $this->text = 'Данные успешно обновлены';
            $this->status = 'success';
            $this->code = 200;
        } else {
            $this->text = $dataVoidDTO->error;
            $this->code = $dataVoidDTO->code;
        }

        return $this->responseJsonApi();
    }


    public function reset(ResetEmailRequest $request): JsonResponse
    {
        $data = $request->validated();

        $dataVoidDTO = $this->service->ResetPassword(data: $data);

        if($dataVoidDTO->status){
            $this->text = 'Данные успешно обновлены';
            $this->status = 'success';
            $this->code = 200;
        } else {
            $this->text = $dataVoidDTO->error;
            $this->code = $dataVoidDTO->code;
        }

        return $this->responseJsonApi();
    }
}



