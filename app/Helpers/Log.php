<?php

namespace App\Helpers;

use JetBrains\PhpStorm\NoReturn;

class Log
{
    /**
     * Return dump of data and die process.
     *
     * @param mixed $data
     * @return void
     */
    #[NoReturn]
    public static function dd(mixed $data): void
    {
        echo '<pre style="border-radius: 3px; background-color: rgba(15, 15, 15, 1); color: white; padding: 8px; line-height: 1.5">';
            var_dump($data);
        echo '</pre>';
        die();
    }
}
