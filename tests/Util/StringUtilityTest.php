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

namespace PolymerMallard\TwigToVue\Test;

use PolymerMallard\TwigToVue\Util;

use PolymerMallard\TwigToVue\Parser;

/**
 * Tests various string functions
 */
class StringUtilityTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests the string between function
     *
     * @return void
     */
    public function testGetStringBetweenTwoStrings()
    {
        $subject = '{% myString %}';
        $start = '{% ';
        $end = ' %}';
        $value = Util\StringUtility::between($subject, $start, $end);

        $this->assertEquals($value, 'myString');
    }

    /**
     * Tests the removing tags
     *
     * @return void
     */
    public function testRemoveTwigTagsFromString()
    {
        $subject = '{{ film.test() }}';
        $value = Util\StringUtility::removeTags($subject);

        $this->assertEquals($value, 'film.test()');
    }
}
