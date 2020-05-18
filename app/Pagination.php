<?php

namespace App;

class Pagination
{
    const PER_PAGE_DEFAULT = 50;

    public static function perPage($perPage): int
    {
        return isset($perPage) && self::isInPaginationRange($perPage)
            ? (int)$perPage
            : self::PER_PAGE_DEFAULT;
    }

    private static function isInPaginationRange($number): bool
    {
        $number = (int)$number;
        return $number > 0 && $number <= self::PER_PAGE_DEFAULT;
    }
}
