<?php

namespace App\Core\Http;

use App\Core\Session;
use Random\RandomException;

class Csrf
{
    /**
     * Generate new token
     *
     * @throws RandomException
     */
    public static function token(): string
    {
        // Session::createIfNotExist();

        return $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    /**
     * Check if token is valid
     *
     * @param string $token
     * @return bool
     */
    public static function validate(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Return current token or create it
     *
     * @return string
     * @throws RandomException
     */
    public static function getToken(): string
    {
        return $_SESSION['csrf_token'] ?? self::token();
    }

    /**
     * @return string
     * @throws RandomException
     */
    public static function template(): string
    {
        $token = htmlspecialchars(self::getToken());

        return <<<EOF
            <input type="hidden"
                name="csrf_token"
                value="$token"
            />
        EOF;
    }
}
