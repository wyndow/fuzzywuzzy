<?php

namespace Gowili\FuzzyWuzzy;

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

    public static function fullProcess($s)
    {
        if (empty ($s)) { return ''; }

        # Keep only Letters and Numbers (see Unicode docs).
        $stringOut = StringProcessor::nonAlnumToWhitespace($s);
        # Force into lowercase.
        $stringOut = StringProcessor::to_lower_case($stringOut);
        # Remove leading and trailing whitespaces.
        $stringOut = StringProcessor::strip($stringOut);

        return $stringOut;
    }
}
