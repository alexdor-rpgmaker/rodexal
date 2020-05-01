<?php

namespace App\Helpers;

class StringParser
{
    public static function html($string): string
    {
        // TODO : Fix encoding?
        return stripslashes($string);
    }

    public static function richText($text, $bbCode = null): string
    {
        $textWithBbCode = self::apiRichText($text, $bbCode);
        return nl2br($textWithBbCode);
    }

    public static function apiRichText($text, $bbCode = null): string
    {
        if(!$bbCode) {
            // TODO : Define this once for the app
            $bbCode = BBCode::construireParserBBCode();
        }
        $textWithEntites = e($text);
        return $bbCode->convertToHtml($textWithEntites);
    }

    public static function parseOrNullify($string)
    {
        return empty($string) ? null : self::html($string);
    }
}
