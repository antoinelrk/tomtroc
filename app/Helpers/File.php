<?php

namespace App\Helpers;

use App\Core\File\Image;

class File
{
    public static function get(?string $filename, string $type): string
    {
        if ($filename !== NULL) {
            return "/../storage/$type/$filename";
        }

        return "/../storage/$type/default.jpg";
    }

    public static function store(string $path, array $file, string $filename = null, int $resolution = null): bool|string
    {
        $filename = $filename ?? Str::basicId(16);
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = "$filename.$extension";
        $fullpath = "./storage/$path/$filename";

        if (move_uploaded_file($file['tmp_name'], $fullpath)) {
            if (file_exists($fullpath)) {
                (new Image())->crop(
                    $file,
                    $fullpath,
                    $resolution ?? 256,
                    $file['type']
                );

                return $filename;
            }

            return false;
        }

        return false;
    }

    public static function delete(string $filename, string $type): void
    {
        unlink("storage/$type/$filename");
    }
}