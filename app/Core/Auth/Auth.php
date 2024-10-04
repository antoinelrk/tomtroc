<?php

namespace App\Core\Auth;

use App\Core\Database;
use App\Core\Notification;
use App\Core\Response;
use App\Enum\EnumNotificationState;
use App\Models\User;
use PDO;
use Random\RandomException;

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
        if (!isset($_SESSION['user'])) {
            return null;
        }

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
     * @return bool|User
     * @throws RandomException
     */
    public static function attempt($email, $password): bool|User
    {
        $user = self::rawUser($email);

        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user'] = serialize($user->withoutPassword());

            return $user;
        }

        return false;
    }

    /**
     * @param string|null $email
     * @return void
     * @throws \Random\RandomException
     */
    public static function refresh(string $email = null): void
    {
        if (isset($_SESSION['user'])) {
            $oldUser = unserialize($_SESSION['user']);
            $newUser = self::rawUser($email ?? $oldUser->email);
            $_SESSION['user'] = serialize($newUser);
        }
    }

    /**
     * @return bool
     */
    public static function logout(): bool
    {
        unset($_SESSION['user']);
        session_destroy();
        session_start();

        return true;
    }

    /**
     * @param string $email
     * @return User
     * @throws \Random\RandomException
     */
    private static function rawUser(string $email): User
    {
        $statement = Database::getInstance()
            ->getConnection()
            ->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindParam(":email", $email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            Notification::push(
                'Vos informations sont incorrects',
                EnumNotificationState::ERROR->value
            );
            Response::redirect('/auth/login');
        }

        return new User($result);
    }
}
