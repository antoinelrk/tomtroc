<?php

namespace App\Helpers;

class Hash
{
    /**
     * Hash the specified string
     *
     * @param string $password
     *
     * @return string
     */
    public static function make(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID);
    }

    /**
     * Check if specified string equals hashed target string
     *
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
