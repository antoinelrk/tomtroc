<?php

namespace App\Core\Facades;

use App\Core\View;

class ViewFacade {
    private static View $view;

    public static function init(View $view) {
        self::$view = $view;
    }

    public static function __callStatic($name, $arguments) {
        return self::$view->$name(...$arguments);
    }
}
