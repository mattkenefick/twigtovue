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
class ConvertFor
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
            case 'for':
                return self::convertFor($str, $outerValue, $attributeValue);

            case 'endfor':
                return self::convertEndFor($str, $outerValue, $attributeValue);
        }
    }

    /**
     * Convert For
     *
     * @param  string $str
     * @param  string $tag
     * @param  string $outerValue
     * @param  string $attributeValue
     *
     * @return string
     */
    private static function convertFor(string $str, string $outerValue, string $attributeValue) : string
    {
        $value = str_replace($outerValue, '<for iterator="' . $attributeValue . '">', $str);

        return $value;
    }

    /**
     * Convert EndFor
     *
     * @param  string $str
     * @param  string $tag
     * @param  string $outerValue
     * @param  string $attributeValue
     *
     * @return string
     */
    private static function convertEndFor(string $str, string $outerValue, string $attributeValue) : string
    {
        $value = str_replace($outerValue, '</for>', $str);

        return $value;
    }

}
