<?php

namespace FuzzyWuzzy;

/**
 * Utility methods for FuzzyWuzzy.
 *
 * @author Michael Crumm <mike@crumm.net>
 */
class Utils
{
    /**
     * Returns a correctly rounded integer.
     *
     * @param float $num Numeric value to round.
     * @return int
     */
    public static function intr($num)
    {
        return (int) round($num, 10, PHP_ROUND_HALF_DOWN);
    }

    /**
     * Returns a string after processing.
     *
     * - Replaces an non-alphanumeric characters with whitespace.
     * - Converts the string to lowercase.
     * - Trims leading/trailing whitespace from the string.
     *
     * @param string  $str        String to process.
     * @param boolean $forceAscii If true, string will be converted to ASCII before processing.
     * @return string
     */
    public static function fullProcess($str, $forceAscii = true)
    {
        if (empty ($str)) { return ''; }

        # TODO: Force ascii, if true

        # Keep only Letters and Numbers (see Unicode docs).
        $stringOut = StringProcessor::nonAlnumToWhitespace($str);
        # Force into lowercase.
        $stringOut = StringProcessor::downcase($stringOut);
        # Remove leading and trailing whitespaces.
        $stringOut = StringProcessor::strip($stringOut);

        return $stringOut;
    }

    /**
     * Validates that a string is a string, and that it has a positive length.
     *
     * @param string $str String to validate.
     * @return boolean
     */
    public static function validateString($str)
    {
        if (!is_string($str)) { return false; }

        return strlen($str) > 0;
    }
}
