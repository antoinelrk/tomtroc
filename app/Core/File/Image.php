<?php

namespace App\Core\File;

use App\Helpers\Log;

class Image
{
    public function crop(array $ofile, string $fullpath, int $max_resolution, string $type)
    {
        if ($ofile['type'] == 'image/jpeg') {
            $original_image = imagecreatefromjpeg($fullpath);
        }
        else {
            $original_image = imagecreatefrompng($fullpath);
        }

        $original_width = imagesx($original_image);
        $original_height = imagesy($original_image);

        $ratio = $max_resolution / $original_width;
        $new_width = $max_resolution;
        $new_height = $original_height * $ratio;

        if ($new_height > $max_resolution) {
            $ratio = $max_resolution / $original_height;
            $new_height = $max_resolution;
            $new_width = $original_width * $ratio;
        }

        if ($original_image) {
            $new_image = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled(
                $new_image,
                $original_image,
                0, 0, 0, 0, $new_width, $new_height,
                $original_width,$original_height
            );

            if ($ofile['type'] == 'image/jpeg') {
                imagejpeg($new_image, $fullpath);
            } else {
                imagepng($new_image, $fullpath);
            }
        }
    }
}