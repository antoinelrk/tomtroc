<?php

use App\Controllers\BooksController;
use App\Controllers\MessagesController;
use App\Core\Router;

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Middlewares\AuthMiddleware;

$router = new Router();

// ---------- DEFAULT ----------

$router->addRoute('GET','/', [HomeController::class, 'index']);

// ---------- AUTHENTICATION ----------

$router->addRoute('GET', '/auth/register', [AuthController::class, 'registerForm']);
$router->addRoute('GET', '/auth/login', [AuthController::class, 'loginForm']);

$router->addRoute('POST', '/auth/register', [AuthController::class, 'register']);
$router->addRoute('POST', LOGIN_ROUTE, [AuthController::class, 'login']);

$router->addRoute('POST', '/auth/logout', [AuthController::class, 'logout'], [ AuthMiddleware::class ]);

// ---------- BOOKS ----------

$router->addRoute('GET', '/our-books', [BooksController::class, 'index']);

// ---------- AUTHENTICATED ----------

$router->addRoute(
    'GET',
    '/me',
    [UserController::class, 'me'],
    [ AuthMiddleware::class ]
);
$router->addRoute(
    'GET',
    '/users/{username}',
    [UserController::class, 'show'],
    [ AuthMiddleware::class ]
);

$router->addRoute(
    'GET',
    '/messages',
    [MessagesController::class, 'index'],
);
$router->addRoute(
    'GET',
    '/messages/{uuid}',
    [MessagesController::class, 'show'],
);
$router->addRoute(
    'POST',
    '/messages',
    [MessagesController::class, 'store'],
);

$router->addRoute(
    'GET',
    '/user-name/{slug}/edit/{id}',
    [UserController::class, 'show']
);

// ---------- API ----------

$router->addRoute('GET',
    '/' . API_PREFIX . '/users',
    [UserController::class, 'index'],
    [AuthMiddleware::class]
);

return $router;
