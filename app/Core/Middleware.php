<?php

namespace App\Core;

use Closure;

interface Middleware
{
    /**
     * Base method for catch request.
     *
     * @param $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next): mixed;
}
