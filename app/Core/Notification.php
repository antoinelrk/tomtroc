<?php

namespace App\Core;

use App\Enum\EnumNotificationState;
use App\Helpers\Str;
use Random\RandomException;

class Notification
{
    public static function as(): bool
    {
        if (count(self::all()) > 0)
        {
            return true;
        }

        return false;
    }

    public static function init(): void
    {
        if (!isset($_SESSION['notifications']))
        {
            $_SESSION['notifications'] = [];
        }
    }

    public static function all(): array
    {
        self::init();

        return $_SESSION['notifications'];
    }

    public static function reset(): void
    {
        $_SESSION['notifications'] = [];
    }

    /**
     * @throws RandomException
     */
    public static function push(string $message, ?string $state = null): array
    {
        $_SESSION['notifications']["n" . Str::basicId()] = [
            'message' => $message,
            'state' => $state ?? EnumNotificationState::INFO->value,
        ];

        return self::all();
    }

    public static function drop($notificationId): array
    {
        if ($notificationId)
        {
            if (isset($_SESSION['notifications'][$notificationId]))
            {
                unset($_SESSION['notifications'][$notificationId]);
            }
        }

        return self::all();
    }
}