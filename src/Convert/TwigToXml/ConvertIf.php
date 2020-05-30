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
     * Tag to close
     *
     * @var null
     */
    private static $previousTag = null;

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
        $attributeValue = self::cleanAttributes($attributeValue);
        $value = str_replace($outerValue, self::getPreviousTag() . '<if condition="' . $attributeValue . '">', $str);
        self::$previousTag = 'if';

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
        $attributeValue = self::cleanAttributes($attributeValue);
        $value = str_replace($outerValue, self::getPreviousTag() . '<elseif condition="' . $attributeValue . '">', $str);
        self::$previousTag = 'elseif';

        return $value;
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
        $attributeValue = self::cleanAttributes($attributeValue);
        $value = str_replace($outerValue, self::getPreviousTag() . '<else condition="' . $attributeValue . '">', $str);
        self::$previousTag = 'else';

        return $value;
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
        switch (self::$previousTag) {
            case 'else':
                $value = str_replace($outerValue, '</else>', $str);
                break;

            case 'elseif':
                $value = str_replace($outerValue, '</elseif>', $str);
                break;

            default:
            case 'if':
                $value = str_replace($outerValue, '</if>', $str);
                break;
        }

        // Unset previous tag because block is closed
        self::$previousTag = null;

        return $value;
    }

    /**
     * Clean attribute values
     *
     * @return string
     */
    private static function cleanAttributes(string $attributeValue): string
    {
        $attributeValue = str_replace("'", "\'", $attributeValue);
        $attributeValue = str_replace('"', '\'', $attributeValue);

        return $attributeValue;
    }

    /**
     * Return closing tag if necessary
     *
     * @return string
     */
    private static function getPreviousTag(): string
    {
        if (self::$previousTag) {
            $tag = self::$previousTag;
            self::$previousTag = null;
            return '</' . $tag . '>';
        }

        return '';
    }

}
