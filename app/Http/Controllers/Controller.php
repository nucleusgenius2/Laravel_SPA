<?php

namespace App\Http\Controllers;

use App\Traits\StructuredResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="API documentation",
 *    version="1.0.0",
 * )
 */
class Controller extends BaseController
{
    use StructuredResponse, AuthorizesRequests, ValidatesRequests;
}
