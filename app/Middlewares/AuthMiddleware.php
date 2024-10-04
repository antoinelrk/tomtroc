<?php

namespace App\Middlewares;

use App\Core\Auth\Auth;
use App\Core\Middleware;
use App\Core\Notification;
use App\Core\Response;
use Closure;
use Random\RandomException;

class AuthMiddleware implements Middleware
{
    /**
     * Authentication middleware for filtering protected pages.
     *
     * @param $request
     * @param Closure $next
     *
     * @return mixed
     * @throws RandomException
     */
    public function handle($request, Closure $next): mixed
    {
        if (!Auth::user()) {
            Notification::push("Vous n'êtes pas autorisé à voir cette page !", 'error');
            Response::redirectToLogin();
            exit;
        }

        return $next($request);
    }
}
