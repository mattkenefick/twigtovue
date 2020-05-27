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
class ConvertLoops
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
        // Apply for loops
        foreach ($queryPath->find('for') as $item) {
            $iterator = $item->attr('iterator');
            preg_match('#([^ ]+) in (.*)$#U', $iterator, $matches);
            list($original, $model, $collection) = $matches;

            // Get child
            $child = $item->children()->first();

            // Apply
            $attributeValue = "($model, index) of $collection";
            $child->attr('v-for', $attributeValue);
            $child->attr('v-bind:key', "index");

            // Remove the for loop
            $child->unwrap();
        }

        return $queryPath->html() ?: '';
    }

}
