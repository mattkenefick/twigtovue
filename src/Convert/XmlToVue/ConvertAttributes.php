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
class ConvertAttributes
{
    /**
     * Attributes we'll convert
     *
     * @var array
     */
    private static $attributesHtml = [
        'class',
        'href',
        'id',
        'style',
    ];

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
        $attributes = self::$attributesHtml;

        foreach ($attributes as $attribute) {
            $elements = $queryPath->find('[' . $attribute . '*="{{"]');

            // Loop through presentation attributes with variables
            foreach ($elements as $element) {
                $attributeValue = $element->attr($attribute);

                // Set Vue style attribute
                $element->attr(':' . $attribute, Util\StringUtility::removeTags($attributeValue));

                // Remove old attribute
                $element->removeAttr($attribute);
            }
        }

        return $queryPath->html() ?: '';
    }

}
