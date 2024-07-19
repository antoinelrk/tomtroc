<?php

use App\Core\Facades\View;
use App\Core\Notification;
use App\Core\ViewRenderer;
use App\Core\Dispatcher;

require_once __DIR__ . '/../vendor/autoload.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
    Notification::init();
}

require_once __DIR__ . '/../app/Core/Constants.php';
$router = require_once __DIR__ . '/../bootstrap/routes.php';

$dispatcher = new Dispatcher($router);

View::init(new ViewRenderer());

$dispatcher->dispatch();
