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

namespace PolymerMallard\TwigToVue;

/**
 * TagIdentifier
 */
class TagIdentifier
{
    /**
     * HTML tags
     *
     * Value can have `tag` to return or
     * we can `convert` it to something else
     *
     * @var array
     */
    private static $tags = [
        'for '     => [ 'tag' => 'for' ],
        'endfor'   => [ 'tag' => 'endfor' ],
        'include ' => [ 'tag' => 'include' ],
        'if '      => [ 'tag' => 'if' ],
        'elseif '  => [ 'tag' => 'elseif' ],
        'else if ' => [ 'tag' => 'elseif' ],
        'else-if ' => [ 'tag' => 'elseif' ],
        'else'     => [ 'tag' => 'else' ],
        'endif'    => [ 'tag' => 'endif' ],
    ];

    /**
     * Try to identify tags
     *
     * The grabbed value is a section, like an innerValue,
     * which would be "include 'xyz' " as opposed to "{% include 'xyz' %}"
     *
     * @param  string $value
     *
     * @return string
     */
    public static function identify(string $value) : string
    {
        // Clean
        $value = self::fixString($value);

        foreach (static::$tags as $key => $options) {
            $possibleKey = substr($value, 0, strlen($key));

            if ($possibleKey === $key) {
                return isset($options['convert'])
                    ? $options['convert']
                    : $options['tag'];
            }
        }

        return '';
    }

    /**
     * Try to replace tags
     *
     * @param  string $value
     *
     * @return string
     */
    public static function replace(string $value) : string
    {
        $identifiedTag = self::identify($value);
        $value = str_replace($identifiedTag, '', $value);

        return self::fixString($value);
    }

    /**
     * Replace tags accidentally added
     *
     * @param  string $value
     *
     * @return string
     */
    private static function fixString(string $value) : string
    {
        $value = str_replace('{%', '', $value);
        $value = str_replace('%}', '', $value);
        $value = trim($value);
        return $value;
    }

}
