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
 * Parser
 *
 * ---
 */
class Parser
{
    /**
     * Test function
     *
     * @return int Test integer
     */
    public static function foo(int $arbitrary): int
    {
        return 1;
    }

}
