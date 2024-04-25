<?php

use App\Core\Router;
use App\Core\Dispatcher;
use App\Controllers\HomeController;

$router = new Router();

/**
 * TODO: Extract routes
 */
$router->addRoute('GET','/', [HomeController::class, 'index']);
$router->addRoute('GET', '/test', [HomeController::class, 'test']);

$dispatcher = new Dispatcher($router);
$dispatcher->dispatch();
