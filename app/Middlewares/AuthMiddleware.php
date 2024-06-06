<?php

namespace App\Middlewares;

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
        if (!isset($_SESSION['user']))
        {
            Response::redirectUnauthorized();
            exit;
        }

        return $next($request);
    }
}
