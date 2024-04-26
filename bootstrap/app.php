<?php

use App\Core\Facades\View;
use App\Core\ViewRenderer;
use App\Core\Router;
use App\Core\Dispatcher;
use App\Controllers\HomeController;

require_once __DIR__ . '/../vendor/autoload.php';

$router = new Router();

$router->addRoute('GET','/', [HomeController::class, 'index']);
$router->addRoute('GET', '/test', [HomeController::class, 'test']);
$router->addRoute('GET', '/users', [HomeController::class, 'users']);

$dispatcher = new Dispatcher($router);

View::init(new ViewRenderer());

$dispatcher->dispatch();
