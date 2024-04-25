<?php

namespace App\Core;

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
     * @return void
     */
    public function dispatch(): void
    {
        $url = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $controllerAction = $this->router->getControllerAction($method, $url);

        if (!empty($controllerAction)) {
            [$controllerClass, $action] = $controllerAction;
            $controller = new $controllerClass();
            $controller->$action();
        } else {
            // TODO: WIP! Return generic errors page
            echo '404 - Page not found';
        }
    }
}
