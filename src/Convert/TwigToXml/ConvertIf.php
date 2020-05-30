<?php

/**
 * This file is part of the PolymerMallard\TwigToVue package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Matt Kenefick <matt@polymermallard.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace PolymerMallard\TwigToVue\Convert\TwigToXml;

/**
 * Converter
 */
class ConvertIf
{

    /**
     * Convert
     *
     * @param  string $str
     * @param  string $tag
     * @param  string $outerValue
     * @param  string $attributeValue
     *
     * @return string
     */
    public static function convert(string $str, string $tag, string $outerValue, string $attributeValue) : string
    {
        switch ($tag) {
            case 'if':
                return self::convertIf($str, $outerValue, $attributeValue);

            case 'elseif':
                return self::convertElseIf($str, $outerValue, $attributeValue);

            case 'else':
                return self::convertElse($str, $outerValue, $attributeValue);

            case 'endif':
                return self::convertEndIf($str, $outerValue, $attributeValue);
        }
    }

    /**
     * Convert If
     *
     * @param  string $str
     * @param  string $tag
     * @param  string $outerValue
     * @param  string $attributeValue
     *
     * @return string
     */
    private static function convertIf(string $str, string $outerValue, string $attributeValue) : string
    {
        $attributeValue = str_replace([' and ', ' or '], [' && ', ' || '], $attributeValue);
        $value = str_replace($outerValue, '<if condition="' . $attributeValue . '">', $str);

        return $value;
    }

    /**
     * Convert ElseIf
     *
     * @param  string $str
     * @param  string $tag
     * @param  string $outerValue
     * @param  string $attributeValue
     *
     * @return string
     */
    private static function convertElseIf(string $str, string $outerValue, string $attributeValue) : string
    {

    }

    /**
     * Convert Else
     *
     * @param  string $str
     * @param  string $tag
     * @param  string $outerValue
     * @param  string $attributeValue
     *
     * @return string
     */
    private static function convertElse(string $str, string $outerValue, string $attributeValue) : string
    {

    }

    /**
     * Convert End If
     *
     * @param  string $str
     * @param  string $tag
     * @param  string $outerValue
     * @param  string $attributeValue
     *
     * @return string
     */
    private static function convertEndIf(string $str, string $outerValue, string $attributeValue) : string
    {
        $value = str_replace($outerValue, '</if>', $str);

        return $value;
    }

}
