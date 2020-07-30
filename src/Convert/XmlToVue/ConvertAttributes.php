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
     * @todo Why don't we convert all attributes that have tags?
     *  I'm guessing because it's easier to NOT do it with query path?
     *
     * @var array
     */
    private static $attributesHtml = [
        'class',
        'href',
        'id',
        'style',
        'title',
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
        $html = $queryPath->html() ?: '';
        $html = urldecode($html);
        $attributes = self::getAttributesFromHtml($html);

        // Parse attributes
        foreach ($attributes as $attribute) {
            $elements = $queryPath->find('[' . $attribute . '*="{{"]');

            // Loop through presentation attributes with variables
            foreach ($elements as $element) {
                $attributeValue = $element->attr($attribute);

                // Set values
                $newAttribute = ':' . $attribute;
                $newValue = $attributeValue;
                // $newValue = Util\StringUtility::removeTags($attributeValue);

                // Convert brackets to quotes
                $newValue = str_replace(['{{', '}}'], ["' + ", " + '"], $newValue);
                $newValue = "'$newValue'";

                // Fix empty brackets
                $newValue = str_replace([
                    "'' + ",
                    "' ' + ",
                    " + ''",
                    " + ' '",
                ], '', $newValue);

                // Trim
                $newValue = trim($newValue);

                // Set Vue style attribute
                $element->attr($newAttribute, $newValue);

                // Remove old attribute
                $element->removeAttr($attribute);
            }
        }

        return $queryPath->html() ?: '';
    }

    /**
     * Extract attributes using variables from HTML
     */
    private static function getAttributesFromHtml(string $html): array
    {
        // v2: Adding the brackets
        $regex = '#\s([a-zA-Z\_\-]+)=["\'][^"\']+["\']#im';

        // v1: Why did we ignore the {{ brackets?
        // $regex = '#\s([a-zA-Z\_\-]+)=["\'](?={{)[^"\']+["\']#im';

        // $regex = '#\s([a-zA-Z\_]+)=["\'](?={{)["\']#im';
        // $html = '<a href="{{ header.href }}" title="{{ header.text">{{ header.title }}</a>';

        // Run matching
        preg_match_all($regex, $html, $matches);

        // Return matches
        if (count($matches) > 1) {
            return array_unique($matches[1]);
        }
        else {
            return self::$attributesHtml;
        }
    }

}
