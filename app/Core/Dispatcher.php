<?php

namespace App\Core;

use App\Core\Facades\View;

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
            [$controllerClass, $action] = $controllerAction;
            $controller = new $controllerClass();
            $controller->$action();
        } else {
            return View::layout('layouts.empty')
                ->withData([
                    'error' => [
                        'code' => 404,
                        'message' => 'Page not found'
                    ],
                ])
                ->view('errors.http-errors')
                ->render();
        }

        return null;
    }
}
