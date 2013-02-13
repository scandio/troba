<?php

namespace Scandio\Util;
/**
 * Static library for string functions
 */
class StringUtils
{
    /**
     * Converts a camel cased string to a lower cased string with a delimiter
     *
     * @param string $camelCasedString the input string as a camelCasedString
     * @param string $delimiter optional the delimiter the default is "-"
     * @return string the result is a camel-cased-string instead of a camelCasedString
     */
    public static function camelCaseTo($camelCasedString, $delimiter = '-')
    {
        return strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/', $delimiter . "$1", $camelCasedString));
    }

    /**
     * Converts a delimited string to a camel cased string
     *
     * @param string $otherString the input string a delimited-string
     * @param string $delimiter optional the delimiter the default is "-"
     * @return string the result is a delimitedString instead of a delimited-string
     */
    public static function camelCaseFrom($otherString, $delimiter = '-')
    {
        return lcfirst(implode('', array_map(function ($data) {
            return ucfirst($data);
        }, explode($delimiter, $otherString))));
    }
}