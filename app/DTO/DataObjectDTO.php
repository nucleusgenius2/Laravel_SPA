<?php

namespace App\DTO;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class DataObjectDTO
{
    readonly bool $status;

    readonly ?string $error;

    readonly ?object $data;

    readonly ?int $code;

    public function __construct(bool $status, ?string $error=null, ?object $data=null, ?int $code=null)
    {
        $this->status = $status;
        $this->error = $error;
        $this->data = $data;
        $this->code = $code;
    }
}
