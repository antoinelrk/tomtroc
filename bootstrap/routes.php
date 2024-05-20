<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Core\Router;

$router = new Router();

$router->addRoute('GET','/', [HomeController::class, 'index']);
$router->addRoute('GET', '/users', [HomeController::class, 'users']);

$router->addRoute('GET', '/login', [AuthController::class, 'login']);

return $router;
