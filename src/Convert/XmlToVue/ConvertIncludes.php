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
class ConvertIncludes
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
        // Apply for includes
        foreach ($queryPath->find('include') as $item) {
            $component = $item->attr('component');
            $attributes = $item->attr();
            array_shift($attributes);

            // Add attributes
            $item->after('<' . $component . ' />');

            foreach ($attributes as $key => $value) {
                $item->next()->attr($key, $value);
            }

            // Apply
            // $attributeValue = "($model, index) in $collection";
            // $child->attr(':v-for', $attributeValue);

            $item->remove();
        }

        return $queryPath->html();
    }

}
