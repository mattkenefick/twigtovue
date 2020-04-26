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

namespace PolymerMallard\TwigToVue\Util;

/**
 * String
 */
class String
{
    /**
     * Get string between two integer points
     *
     * @param  string $str
     * @param  int $start Starting position
     * @param  int $end   Ending position
     * @return string
     */
    public static function between(string $str, int $start, int $end): string
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);

        if ($ini == 0) {
            return '';
        }

        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        return substr($string, $ini, $len);
    }

    /**
     * Remove tags
     *
     * @param  string $str
     * @return string
     */
    public static function removeTags(string $str): string
    {
        return trim(preg_replace('#({{|}})#Um', '', $str));
    }
}
