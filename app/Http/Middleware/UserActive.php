<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class UserActive
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response|string
     */
    public function handle(Request $request, Closure $next): Response|string
    {
        $user = request()->user();

        if ($user->status == 1) {
            return $next($request);
        } else {
            return 'user banned';
        }

    }
}
