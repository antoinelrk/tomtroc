<?php

namespace App\Helpers;

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
}
