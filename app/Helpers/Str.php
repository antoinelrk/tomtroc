<?php

namespace App\Helpers;

use ReflectionClass;

class Str
{
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
}