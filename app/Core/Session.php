<?php

namespace App\Core;

class Session
{
    public static function exist(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public static function createIfNotExist(): void
    {
        if (!self::exist()) session_start();
    }
}