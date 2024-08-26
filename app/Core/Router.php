<?php

namespace App\Core;

use App\Middlewares\CsrfMiddleware;

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
     * @param array $middlewares
     *
     * @return void
     */
    public function addRoute(string $method, string $route, array $controllerAction, array $middlewares = []): void
    {
        $this->routes[$method][$route] = [
            'controllerAction' => $controllerAction,
            'middlewares' => [
                ...$middlewares,
                ...$this->globalMiddlewares()
            ],
            'parameters' => [],
        ];
    }

    /**
     * Return route action classes.
     *
     * @param string $method
     * @param string $route
     *
     * @return array
     */
    public function getControllerAction(string $method, string $route): array
    {
        foreach ($this->routes[$method] as $definedRoute => $action)
        {
            if ($parameters = $this->match($definedRoute, $route))
            {
                return [
                    'controllerAction' => $action['controllerAction'],
                    'middlewares' => $action['middlewares'],
                    'parameters' => $parameters,
                ];
            }
        }

        return $this->routes[$method][$route] ?? [];
    }

    private function match(string $definedRoute, string $requestedRoute): false|array
    {
        // Extraire les clés des accolades
        /*
        preg_match_all('/\{([^}]+)}/', $definedRoute, $keys);
        $keys = $keys[1];
        */

        $pattern = preg_replace('/\{[^}]+}/', '([^/]+)', $definedRoute);
        $pattern = "@^{$pattern}$@";

        if (preg_match($pattern, $requestedRoute, $matches))
        {
            array_shift($matches); // Remove the full match

            return $matches;
        }
        return false;
    }

    private function globalMiddlewares(): array
    {
        return [
            CsrfMiddleware::class,
        ];
    }
}
