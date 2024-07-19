<?php

namespace App\Core;

use App\Core\File\Image;
use App\Helpers\Log;
use App\Helpers\Str;

class File
{
    public static function store(string $path, array $file, string $filename = null): bool|string
    {
        $filename = $filename ?? Str::basicId(16);
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fullpath = $path . $filename . '.' . $extension;

        if (move_uploaded_file($file['tmp_name'], $fullpath)) {
            if (file_exists($fullpath)) {
                (new Image())->crop(
                    $file,
                    $fullpath,
                    256,
                    $file['type']
                );

                return $fullpath;
            }

            return false;
        }

        return false;
    }
}