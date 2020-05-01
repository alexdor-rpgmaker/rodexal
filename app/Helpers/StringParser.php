<?php

namespace App\Helpers;

class StringParser
{
    public static function html($string): string
    {
        // TODO
        return $string;
    }

    public static function parseOrNullify($string)
    {
        return empty($string) ? null : self::html($string);
    }
}
