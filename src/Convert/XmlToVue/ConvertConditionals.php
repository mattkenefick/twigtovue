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
        // Check for conditionals
        foreach ($queryPath->find('if') as $item) {
            $condition = $item->attr('condition');

            // Get child
            $child = $item->children()->first();

            // Apply
            $attributeValue = $condition;
            $child->attr(':v-if', $attributeValue);

            // Remove the for loop
            $child->unwrap();
        }

        return $queryPath->html() ?: '';
    }

}
