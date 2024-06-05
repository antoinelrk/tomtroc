<?php

use App\Core\Router;

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Middlewares\AuthMiddleware;

$router = new Router();

$router->addRoute('GET','/', [HomeController::class, 'index']);

// Authentication
$router->addRoute('GET', '/auth/register', [AuthController::class, 'registerForm']);
$router->addRoute('GET', '/auth/login', [AuthController::class, 'loginForm']);

$router->addRoute('POST', '/auth/register', [AuthController::class, 'register']);
$router->addRoute('POST', LOGIN_ROUTE, [AuthController::class, 'login']);

$router->addRoute('POST', '/auth/logout', [AuthController::class, 'logout']);

// Authenticated TODO: Push in group system with middleware
$router->addRoute(
    'GET',
    '/me',
    [UserController::class, 'me'],
    [ AuthMiddleware::class ]
);

// API routes
$router->addRoute('GET',
    '/' . API_PREFIX . '/users',
    [UserController::class, 'index'],
    [AuthMiddleware::class]
);
/**
 * TODO: Create abstract methods (get(), post(), put() ...)
 * TODO: Create fluent methods for group, prefix, controller and middleware
 */

return $router;
