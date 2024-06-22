<?php

namespace App\Core;

use App\Core\Facades\View;
use App\Helpers\Errors;

readonly class Dispatcher
{
    /**
     * @param Router $router
     */
    public function __construct(
        private Router $router
    ) {}

    /**
     * Dispatch routes methods.
     *
     * @return View|null
     */
    public function dispatch(): View|null
    {
        $url = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $controllerAction = $this->router->getControllerAction($method, $url);

        if (!empty($controllerAction)) {
            [$controllerClass, $action] = $controllerAction['controllerAction'];
            $middlewares = $controllerAction['middlewares'];

            $middlewareManager = new MiddlewareManager();

            foreach ($middlewares as $middleware) {
                $middlewareManager->add(new $middleware);
            }

            $processedRequest = $middlewareManager->handle($url);

            if ($processedRequest) {
                $controller = new $controllerClass();

                call_user_func_array(
                    [$controller, $action],
                    $controllerAction['parameters'] ?? []
                );

                // $controller->$action();
            }
        } else {
            return Errors::notFound();
        }

        return null;
    }
}
