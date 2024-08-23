<?php

namespace App\Helpers;

use App\Models\Model;

class ArrayHelper
{
    public static function map(array $values, string $model): array
    {
        return array_combine((new $model)->map, $values);
    }

    public static function normalize(array $values, string $prefix): array
    {
        return array_filter($values, function ($value, $key) use ($values, $prefix) {
            if ($key !== null && str_starts_with($key, $prefix)) {
                return true;
            }
            return false;
        }, true);
    }
}