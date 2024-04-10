<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $route, string $controllerAction): void
    {
        $this->routes[$method][$route] = $controllerAction;
    }

    public function getControllerAction(string $method, string $route): string
    {
        return $this->routes[$method][$route] ?? '';
    }
}
