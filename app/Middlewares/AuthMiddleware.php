<?php

namespace App\Middlewares;

use App\Core\Middleware;
use App\Core\Response;
use Closure;

class AuthMiddleware implements Middleware
{
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
