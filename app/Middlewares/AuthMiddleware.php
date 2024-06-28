<?php

namespace App\Middlewares;

use App\Core\Auth\Auth;
use App\Core\Middleware;
use App\Core\Response;
use Closure;

class AuthMiddleware implements Middleware
{
    /**
     * Authentication middleware for filtering protected pages.
     *
     * @param $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next): mixed
    {
        if (!Auth::user())
        {
            Response::redirectToLogin();
            exit;
        }

        return $next($request);
    }
}
