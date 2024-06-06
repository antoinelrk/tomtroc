<?php

namespace App\Core;

class MiddlewareManager
{
    /**
     * List of middlewares.
     *
     * @var array
     */
    protected array $middlewares = [];

    /**
     * Index of ordered middlewares.
     *
     * @var int
     */
    protected int $index = 0;

    /**
     * Push new middleware in middleware list.
     *
     * @param $middleware
     *
     * @return void
     */
    public function add($middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * Handle middleware one by one & execute.
     *
     * @param $request
     *
     * @return mixed
     */
    public function handle($request): mixed
    {
        if ($this->index < count($this->middlewares)) {
            $middleware = $this->middlewares[$this->index];
            $this->index++;

            return $middleware->handle($request, function($request) {
                return $this->handle($request);
            });
        }
        return $request;
    }
}
