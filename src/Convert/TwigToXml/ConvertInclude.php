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

use PolymerMallard\TwigToVue\Util;

/**
 * Converter
 */
class ConvertInclude
{

    /**
     * Prevents us from creating attributes for object references
     * and only allows literal values, like :property="'String'"
     * as opposed to :foo="bar"
     *
     * @var boolean
     */
    public static $onlyLiteralAttributes = true;

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
            case 'include':
                return self::convertInclude($str, $outerValue, $attributeValue);

        }
    }

    /**
     * Convert Include
     *
     * @param  string $str
     * @param  string $tag
     * @param  string $outerValue
     * @param  string $attributeValue
     *
     * @return string
     */
    private static function convertInclude(string $str, string $outerValue, string $attributeValue) : string
    {
        // Take the latter half of the concatenation to simplify the regex
        $a = strpos($attributeValue, ' ~ ') > 1
            ? substr($attributeValue, strpos($attributeValue, ' ~ ') + 3)
            : $attributeValue;

        // Convert outerValue to use new attributeValue
        $b = str_replace($attributeValue, $a, $outerValue);

        // Parse out the filename
        // This new regex asks for the last version
        preg_match('#(\'|\")((.*)\/?)(\.twig)?(\'|\")\s+?(?:%}|with)#U', $b, $matches);

        // Get items within the quotes, "view/inner/foo/bar.twig"
        $matches = array_slice($matches, 2, -2);
        $filepath = str_replace('.twig', '', $matches[0]);
        $filepath = preg_replace('#[^a-zA-Z0-9\/]#U', '', $filepath);

        // Break up path into words
        $parts = explode('/', $filepath);

        // Check if last two items the same
        if (count($parts) >= 2 && end($parts) === $parts[count($parts) - 2]) {
            array_pop($parts);
        }

        // Combine into things like ViewInnerFooBar
        $component = implode('', array_map('ucfirst', $parts));

        // Attributes between with { } brackets
        $attributes = '';
        $with = Util\StringUtility::between($attributeValue, '{', '}');
        preg_match_all('#[ \n](.*)\:(.*)[,\n]#Us', $with, $matches);

        // Iterate through matches
        if (count($matches[0])) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $key = trim($matches[1][$i]);
                $value = str_replace('"', '\'', trim($matches[2][$i]));
                $isLiteral = strpos($value, "'") === 0;

                // If we have a key, value, and it's a literal
                if ($key && $value && ($isLiteral && self::$onlyLiteralAttributes)) {
                    $attributes .= ':' . $key . '="' . $value . '" ';
                }
            }
        }

        $value = str_replace($outerValue, '<include component="' . $component . '" ' . $attributes . ' />', $str);

        return $value;
    }

}
