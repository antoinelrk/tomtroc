<?php

namespace App\Core\File;

use App\Helpers\Log;

class Image
{
    public function crop(string $file, int $width, int $height)
    {
        $image = imagecreatefromstring(file_get_contents($file));

        Log::dd($image);
    }
}