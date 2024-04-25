<?php

namespace App\Helpers;

class Log
{
    public static function dd(mixed $data): void
    {
        echo '<pre style="background-color: rgba(15, 15, 15, 1); color: white; padding: 8px; line-height: 1.5">';
            var_dump($data);
        echo '</pre>';
    }
}
