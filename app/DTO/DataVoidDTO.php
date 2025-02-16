<?php

namespace App\DTO;

class DataVoidDTO
{
    readonly bool $status;

    readonly ?string $error;

    readonly ?int $code;

    public function __construct(bool $status, ?string $error=null, ?int $code=null)
    {
        $this->status = $status;
        $this->error = $error;
        $this->code = $code;
    }
}
