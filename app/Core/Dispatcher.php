<?php

namespace App\Core;

readonly class Dispatcher
{
    public function __construct(
        private Router $router
    ) {}

    /**
     * Dispatch routes methods.
     *
     * @return void
     */
    public function dispatch(): void
    {
        $url = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $controllerAction = $this->router->getControllerAction($method, $url);

        if (!empty($controller)) {
            [$controllerClassName, $action] = explode('@', $controllerAction);
            $controller = new $controllerClassName();
            $controller->$action();
        } else {
            // TODO: Return to error route (404)
            echo '404';
        }
    }
}
