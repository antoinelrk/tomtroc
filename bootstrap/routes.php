<?php

use App\Controllers\BooksController;
use App\Controllers\ConversationsController;
use App\Controllers\MessagesController;
use App\Controllers\NotificationsController;
use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Middlewares\AuthMiddleware;

$router = new Router();

// ---------- DEFAULT ----------

$router->addRoute('GET', '/', [HomeController::class, 'index']);

// ---------- AUTHENTICATION ----------

$router->addRoute('GET', '/auth/register', [AuthController::class, 'registerForm']);
$router->addRoute('GET', '/auth/login', [AuthController::class, 'loginForm']);

$router->addRoute('POST', '/auth/register', [AuthController::class, 'register']);
$router->addRoute('POST', LOGIN_ROUTE, [AuthController::class, 'login']);

$router->addRoute('POST', '/auth/logout', [AuthController::class, 'logout'], [ AuthMiddleware::class ]);

// ---------- USERS ----------

$router->addRoute('POST', '/users/update/{id}', [UserController::class, 'update'], [AuthMiddleware::class]);

// ---------- BOOKS ----------

$router->addRoute('GET', '/our-books', [BooksController::class, 'index']);
$router->addRoute('GET', '/books/show/{id}', [BooksController::class, 'show'], [AuthMiddleware::class]);
$router->addRoute('GET', '/books/create', [BooksController::class, 'create'], [AuthMiddleware::class]);
$router->addRoute('GET', '/books/edit/{slug}', [BooksController::class, 'edit'], [AuthMiddleware::class]);
$router->addRoute('POST', '/books/store', [BooksController::class, 'store'], [AuthMiddleware::class]);
$router->addRoute('POST', '/books/update/{id}', [BooksController::class, 'update'], [AuthMiddleware::class]);

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

// ---------- MESSAGING ----------

$router->addRoute('GET', '/conversations/create/{id}', [ConversationsController::class, 'create']);
$router->addRoute('GET', '/conversations/show', [ConversationsController::class, 'index']);
$router->addRoute('GET', '/conversations/show/{uuid}', [ConversationsController::class, 'show']);
$router->addRoute('GET', '/conversations/no-message', [ConversationsController::class, 'noMessage']);
$router->addRoute('POST', '/messages/store', [MessagesController::class, 'store'], );

$router->addRoute(
    'GET',
    '/new-message/{id}',
    [ConversationsController::class, 'create'],
);

// ---------- NOTIFICATIONS ----------

$router->addRoute(
    'POST',
    '/notifications/drop/{id}',
    [NotificationsController::class, 'drop']
);

$router->addRoute(
    'GET',
    '/' . API_PREFIX . '/users',
    [UserController::class, 'index'],
    [AuthMiddleware::class]
);

return $router;
