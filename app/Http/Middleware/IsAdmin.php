<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;


class IsAdmin
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response|string
     */
    public function handle(Request $request, Closure $next): Response|string
    {
        $user = request()->user();


        if (Gate::allows('is_admin')) {
            return $next($request);
        } else {
            return response('Недостаточно прав', 303);
        }

    }
}
