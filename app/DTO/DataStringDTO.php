<?php

namespace App\DTO;

class DataStringDTO
{
    readonly public bool $status;

    readonly public ?string $error;

    readonly public ?string $data;

    readonly public ?int $code;

    public function __construct(bool $status, ?string $error=null, ?string $data=null, ?int $code=null)
    {
        $this->status = $status;
        $this->error = $error;
        $this->data = $data;
        $this->code = $code;
    }
}
