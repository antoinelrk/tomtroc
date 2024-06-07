<?php

namespace App\Core\Auth;

use App\Core\Database;
use App\Models\User;

class Auth
{
    /**
     * Return if user exist in $_SESSION var.
     *
     * @return bool
     */
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Return current authenticated user.
     *
     * @return array
     */
    public static function user(): array
    {
        return $_SESSION['user'];
    }

    /**
     * Attempt to login user.
     *
     * @param $email
     * @param $password
     *
     * @return bool
     */
    public static function attempt($email, $password): bool
    {
        $user = self::fetchUser($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = (new User())->withoutHidden($user);
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public static function logout(): bool
    {
        unset($_SESSION['user']);

        return true;
    }

    /**
     * Fetch user WITH the password.
     *
     * @param string $email
     *
     * @return array|null
     */
    private static function fetchUser(string $email): array|null
    {
        $connection = Database::getInstance()->getConnection();
        $statement = $connection->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindParam(':email', $email);
        $statement->execute();

        return $statement->fetch();
    }
}
