<?php

namespace App\Core\Auth;

use App\Core\Database;
use App\Models\Model;
use App\Models\User;
use PDO;

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
     * @return User
     */
    public static function user(): ?User
    {
        $user = unserialize($_SESSION['user']);
        if (isset($user)) {
            return $user;
        }

        return null;
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
        $user = self::rawUser($email);

        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user'] = serialize($user->unsetHiddenAttributes($user));

            return true;
        }

        return false;
    }

    public static function refresh(): void
    {
        if (isset($_SESSION['user'])) {
            $_SESSION['user'] = (new User())
                ->where('id', Auth::user()?->id)
                ->first();
        }
    }

    /**
     * @return bool
     */
    public static function logout(): bool
    {
        unset($_SESSION['user']);

        return true;
    }

    private static function rawUser(string $email): ?Model
    {
        $statement = Database::getInstance()
            ->getConnection()
            ->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindParam(":email", $email);
        $statement->setFetchMode(PDO::FETCH_CLASS, User::class);
        $statement->execute();

        return $statement->fetch();
    }
}
