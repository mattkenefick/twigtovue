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
     * Depth
     *
     * @var int
     */
    private static $depth = 0;

    /**
     * Previous tags
     *
     * @var array
     */
    private static $tagHistory = [];

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
        // $previousTag = self::getPreviousTag();
        $previousTag = end(self::$tagHistory) != 'if' ? self::getPreviousTag() : '';
        $attributeValue = self::cleanAttributes($attributeValue);
        $value = self::str_replace_first($outerValue, $previousTag . '<if condition="' . $attributeValue . '">', $str);

        // increase depth
        self::$depth++;

        // add conditional to history
        self::addPreviousTag('if');

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
        $previousTag = self::getPreviousTag();
        $attributeValue = self::cleanAttributes($attributeValue);
        $value = self::str_replace_first($outerValue, $previousTag . '<elseif condition="' . $attributeValue . '">', $str);

        // add conditional to history
        self::addPreviousTag('elseif');

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
        $previousTag = self::getPreviousTag();
        $attributeValue = self::cleanAttributes($attributeValue);
        $value = self::str_replace_first($outerValue, $previousTag . '<else condition="' . $attributeValue . '">', $str);

        // add conditional to history
        self::addPreviousTag('else');

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
        $previousTag = self::getPreviousTag();

        $value = self::str_replace_first($outerValue, $previousTag, $str);

        // $value = str_replace($outerValue, $previousTag . "\n", $str);
        // switch ($previousTag) {
        //     case '</else>':
        //         $value = str_replace($outerValue, '</else>', $str);
        //         break;

        //     case '</elseif>':
        //         $value = str_replace($outerValue, '</elseif>', $str);
        //         break;

        //     default:
        //     case '</if>':
        //         $value = str_replace($outerValue, '</if>', $str);
        //         break;
        // }

        // decrease depth
        self::$depth--;

        // Unset previous tag because block is closed
        // self::$previousTag = null;

        return $value;
    }

    /**
     * Clean attribute values
     *
     * XML validation specifically has an issue with "<" even when
     * wrapped within quotes. Safer here to just convert both.
     *
     * @return string
     */
    private static function cleanAttributes(string $attributeValue): string
    {
        $attributeValue = str_replace("'", "\'", $attributeValue);
        $attributeValue = str_replace('"', '\'', $attributeValue);
        $attributeValue = str_replace('<', '&lt;', $attributeValue);
        $attributeValue = str_replace('>', '&gt;', $attributeValue);

        return $attributeValue;
    }

    /**
     * Return closing tag if necessary
     *
     * @return string
     */
    private static function addPreviousTag(string $tag): void
    {
        // echo ' o tag = ' . $tag . "   (" . implode(', ', self::$tagHistory) . ") \n";

        self::$tagHistory[] = $tag;
    }
    /**
     * Return closing tag if necessary
     *
     * @return string
     */
    private static function getPreviousTag(): string
    {
        if (count(self::$tagHistory)) {
            // echo ' x ' . (end(self::$tagHistory)) . "   {" . implode(', ', self::$tagHistory) . "} \n";

            $tag = array_pop(self::$tagHistory);
            return '</' . $tag . '>';
        }

        return '';
    }

    /**
     * Replace only first instance
     *
     * @param  string $from
     * @param  string $to
     * @param  string $content
     * @return string
     */
    private static function str_replace_first(string $from, string $to, string $content): string
    {
        $from = '#' . preg_quote($from, '#') . '#';

        return preg_replace($from, $to, $content, 1);
    }

}
