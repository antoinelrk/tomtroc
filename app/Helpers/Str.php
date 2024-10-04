<?php

namespace App\Helpers;

use Random\RandomException;
use ReflectionClass;

class Str
{
    /**
     * @return string
     * @throws RandomException
     */
    public static function uuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * @param string $text
     * @return string
     */
    public static function slug(string $text): string
    {
        $text = self::transliterate($text);
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * @throws RandomException
     */
    public static function basicId(?int $length = 8): string
    {
        return substr(bin2hex(random_bytes($length)), 0, $length);
    }

    /**
     * @param $classname
     * @return string
     */
    public static function setDatatable($classname): string
    {
        $result = "";

        try {
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
        } catch (\ReflectionException $exception) {
        }

        return $result;
    }

    /**
     * @param $string
     * @param $length
     * @return string
     */
    public static function trunc($string, $length): string
    {
        if (strlen($string) <= $length) {
            return $string;
        }
        return substr($string, 0, $length) . ' ...';
    }

    /**
     * @param $text
     * @return string
     */
    public static function transliterate($text): string
    {
        $transliterationTable = [
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
            'ß' => 'ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae',
            'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i',
            'ï' => 'i', 'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
            'ő' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y',
            'þ' => 'th', 'ÿ' => 'y'
        ];

        return strtr($text, $transliterationTable);
    }

    /**
     * @param int $number
     * @param string $text
     * @return string
     */
    public static function plurialize(int $number, string $text): string
    {
        if ($number === 0) {
            return "Aucun(e) $text";
        }

        if ($number > 1) {
            return "$number {$text}s";
        } else {
            return "$number $text";
        }
    }
}
