<?php

namespace App\Helpers;

use DateTime;
use Exception;

class Diamond
{
    /**
     * Return now date.
     *
     * @param ?string $format
     *
     * @return string
     */
    public static function now(string $format = null): string
    {
        return date($format ?? DEFAULT_DATE_FORMAT);
    }

    /**
     * @throws Exception
     */
    public static function format(string $date = null, string $format = null): string
    {
        return date(
            $format ?? DEFAULT_DATE_FORMAT,
            (new DateTime($date))->getTimestamp() ?? new DateTime(),
        );
    }

    /**
     * @throws Exception
     */
    public static function diffForHumans(string $date, bool $prefixed = false): string
    {
        try {
            $now = new DateTime();
            $date = new DateTime($date);
            $interval = $now->diff($date);

            if ($interval->y > 0) {
                return ($prefixed ? 'il y a ' : '') . $interval->y . ' an' . ($interval->y > 1 ? 's' : '');
            }

            if ($interval->m > 0) {
                return ($prefixed ? 'il y a ' : '') . $interval->m . ' mois';
            }

            if ($interval->d > 0) {
                return ($prefixed ? 'il y a ' : '') . $interval->d . ' jour' . ($interval->d > 1 ? 's' : '');
            }

            if ($interval->h > 0) {
                return ($prefixed ? 'il y a ' : '') . $interval->h . ' heure' . ($interval->h > 1 ? 's' : '');
            }

            if ($interval->i > 0) {
                return ($prefixed ? 'il y a ' : '') . $interval->i . ' minute' . ($interval->i > 1 ? 's' : '');
            }

            return ($prefixed ? 'il y a ' : '') . $interval->s . ' seconde' . ($interval->s > 1 ? 's' : '');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
