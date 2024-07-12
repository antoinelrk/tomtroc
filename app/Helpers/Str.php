<?php

namespace App\Helpers;

use Random\RandomException;
use ReflectionClass;

class Str
{
    /**
     * @throws RandomException
     */
    public static function basicId(?int $length = 8): string
    {
        return substr(bin2hex(random_bytes($length)), 0, $length);
    }

    public static function setDatatable($classname): string
    {
        $result = "";

        try
        {
            $classname = (new ReflectionClass($classname))
                ->getShortName();
            $result = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $classname));

            if (str_ends_with($result, 'y')) {
                $result = substr($result, 0, -1) . 'ies';
            } elseif (str_ends_with($result, 's')) {
                $result .= 'es';
            } else {
                $result .= 's';
            }
        } catch (\ReflectionException $exception) {}

        return $result;
    }

    public static function trunc($string, $length): string
    {
        if (strlen($string) <= $length) {
            return $string;
        }
        return substr($string, 0, $length) . ' ...';
    }
}