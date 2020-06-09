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

namespace PolymerMallard\TwigToVue\Convert\XmlToVue;

use PolymerMallard\TwigToVue\Util;

/**
 * Converter
 */
class ConvertConditionals
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
    public static function convert(object $queryPath) : string
    {
        self::_convert($queryPath, 'if');
        self::_convert($queryPath, 'elseif', 'else-if');
        self::_convert($queryPath, 'else');

        return $queryPath->html() ?: '';
    }

    /**
     *
     */
    private static function _convert(object $queryPath, string $searchType, string $attributeType = null)
    {
        // Convert one type to another
        if ($attributeType === null) {
            $attributeType = $searchType;
        }

        // Check for conditionals
        foreach ($queryPath->find($searchType) as $item) {
            $condition = $item->attr('condition');

            // Get child
            $child = $item->children()->first();

            // Apply
            $attributeValue = $condition;
            $attributeValue = str_replace([' and ', ' or '], [' && ', ' || '], $attributeValue);
            $attributeValue = str_replace(['&gt;', '&lt;'], ['>', '<'], $attributeValue);

            $child->attr('v-' . $attributeType, $attributeValue);

            // Remove the for loop
            $child->unwrap();
        }

        return $queryPath;
    }

}
