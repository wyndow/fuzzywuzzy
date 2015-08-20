<?php

namespace Gowili\Wuzzy;

class Utils
{
    /**
     * Returns a correctly rounded integer.
     *
     * @param float $n
     * @return int
     */
    public static function intr($n)
    {
        return (int) round($n, 10, PHP_ROUND_HALF_DOWN);
    }
}
