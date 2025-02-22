<?php

namespace App\DTO;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class DataArrayDTO
{
    readonly bool $status;

    readonly ?string $error;

    readonly ?array $data;

    readonly ?int $code;

    public function __construct(bool $status, ?string $error=null, ?array $data=null, ?int $code=null)
    {
        $this->status = $status;
        $this->error = $error;
        $this->data = $data;
        $this->code = $code;
    }
}
