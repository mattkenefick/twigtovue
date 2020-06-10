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
class StringUtility
{
    /**
     * Get string between two integer points
     *
     * @param  string $str
     * @param  string $start Starting string
     * @param  string $end   Ending string
     * @return string
     */
    public static function between(string $str, string $start, string $end, bool $fromEnd = false): string
    {
        $str = ' ' . $str;
        $ini = strpos($str, $start);

        if ($ini == 0) {
            return '';
        }

        $ini += strlen($start);
        $len = $fromEnd
            ? strrpos($str, $end, $ini) - $ini
            : strpos($str, $end, $ini) - $ini;

        return substr($str, $ini, $len);
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
