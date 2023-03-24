<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class AdminPermission
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response|string
     */
    public function handle(Request $request, Closure $next): Response|string
    {
        $user = request()->user();

        if ($user->tokenCan('permission:admin')) {
            return $next($request);
        } else {
            return 'not permission';
        }

    }
}
