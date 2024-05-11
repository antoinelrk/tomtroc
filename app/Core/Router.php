<?php

namespace App\Core;

class Router
{
    /**
     * @var array
     */
    private array $routes = [];

    /**
     * Push route in routes array
     *
     * @param string $method
     * @param string $route
     * @param array $controllerAction
     * @return void
     */
    public function addRoute(string $method, string $route, array $controllerAction): void
    {
        $this->routes[$method][$route] = $controllerAction;
    }

    /**
     * Return route action classes.
     *
     * @param string $method
     * @param string $route
     * @return array
     */
    public function getControllerAction(string $method, string $route): array
    {
        return $this->routes[$method][$route] ?? [];
    }
}
