<?php

namespace App\Core\Auth;

use App\Core\Database;
use App\Helpers\Log;
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
            $_SESSION['user'] = serialize($user->withoutPassword());

            return true;
        }

        return false;
    }

    public static function refresh(): void
    {
        if (isset($_SESSION['user'])) {
            $oldUser = unserialize($_SESSION['user']);
            $newUser = self::rawUser($oldUser->email);
            $_SESSION['user'] = serialize($newUser);
        }
    }

    /**
     * @return bool
     */
    public static function logout(): bool
    {
        // TODO: Retirer les notifications, le user
        unset($_SESSION['user']);
        session_destroy();

        return true;
    }

    private static function rawUser(string $email): User
    {
        $statement = Database::getInstance()
            ->getConnection()
            ->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindParam(":email", $email);
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $statement->execute();

        return new User($statement->fetch(PDO::FETCH_ASSOC));
    }
}
