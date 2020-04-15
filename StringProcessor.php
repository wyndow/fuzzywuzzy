<?php

namespace k\util\fuzzy;

/**
 * Convenience methods for working with string values.
 *
 * @author Michael Crumm <mike@crumm.net>
 */
class StringProcessor
{
    /**
     * @param $str
     * @return string
     */
    public static function nonAlnumToWhitespace($str)
    {
        return mb_ereg_replace('/(?i)\W/u', ' ', $str);//preg_replace('/(?i)\W/u', ' ', $str);
    }

    /**
     * @param $str
     * @return string
     */
    public static function upcase($str)
    {
        return mb_strtoupper($str);
    }

    /**
     * @param $str
     * @return string
     */
    public static function downcase($str)
    {
        return mb_strtolower($str);
    }

    /**
     * @param $pieces
     * @param string $glue
     * @return string
     */
    public static function join($pieces, $glue = ' ')
    {
        return Collection::coerce($pieces)->join($glue);
    }

    /**
     * @param $str
     * @param string $delimiter
     * @return Collection
     */
    public static function split($str, $delimiter = ' ')
    {
        //return new Collection(explode($delimiter, $str));
      return new Collection(mb_split($delimiter, $str));
    }

    /**
     * @param $str
     * @param string $chars
     * @return string
     */
    public static function strip($str, $chars = " \t\n\r\0\x0B")
    {
        return trim($str, $chars);
    }
}
