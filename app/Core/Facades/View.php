<?php

namespace App\Core\Facades;

use App\Core\ViewRenderer;

class View {
    private static ViewRenderer $view;

    public static function init(ViewRenderer $view): void
    {
        self::$view = $view;
    }

    public static function __callStatic($name, $arguments) {
        return self::$view->$name(...$arguments);
    }
}
