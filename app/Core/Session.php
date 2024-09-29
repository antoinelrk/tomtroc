<?php

namespace App\Core;

class Session
{
    /**
     * @return bool
     */
    public static function exist(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * @return void
     */
    public static function createIfNotExist(): void
    {
        if (!self::exist()) session_start();
    }
}