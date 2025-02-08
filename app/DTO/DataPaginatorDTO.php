<?php

namespace App\DTO;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class DataPaginatorDTO
{
    readonly bool $status;

    readonly ?string $error;

    readonly ?LengthAwarePaginator $data;

    public function __construct(bool $status, ?string $error=null, ?LengthAwarePaginator $data=null)
    {
        $this->status = $status;
        $this->error = $error;
        $this->data = $data;
    }
}
