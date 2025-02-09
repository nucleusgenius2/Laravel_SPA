<?php

namespace App\DTO;

class DataEmptyDTO
{
    readonly bool $status;

    readonly ?string $error;

    public function __construct(bool $status, ?string $error=null)
    {
        $this->status = $status;
        $this->error = $error;
    }
}
