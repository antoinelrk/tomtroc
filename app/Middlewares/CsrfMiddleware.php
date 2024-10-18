<?php

namespace App\Middlewares;

use App\Core\Http\Csrf;
use App\Core\Middleware;
use App\Helpers\Errors;
use Closure;
use HttpResponseException;

class CsrfMiddleware implements Middleware
{
    /**
     * @throws HttpResponseException
     * @throws \Exception
     */
    public function handle($request, Closure $next): mixed
    {
        // WIP: Faire une class request ?
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !str_contains($_SERVER['REQUEST_URI'], 'notification')) {
            $csrfToken = filter_var($_POST['csrf_token'], FILTER_SANITIZE_SPECIAL_CHARS);
            $token = $csrfToken ?? '';

            if (!$token || !Csrf::validate($token)) {
                return Errors::expired();
            }
        }

        unset($_POST['csrf_token']);

        return $next($request);
    }
}
