<?php

namespace App\Helpers;

class Diamond
{
    /**
     * Return now date
     *
     * @return string
     */
    public static function now(): string
    {
        return date('Y-m-d h:i:s');
    }
}
