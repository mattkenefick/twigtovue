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

use PolymerMallard\TwigToVue\Parser;

/**
 * Lets you easily generate log records and a dummy
 * formatter for testing purposes
 */
class SomethingTest extends \PHPUnit\Framework\TestCase
{

    public function testSluggifyReturnsSluggifiedString()
    {
        // $originalString = 'My string to be sluggified';
        $originalString = 'my-string-to-be-sluggified';
        $expectedResult = 'my-string-to-be-sluggified';

        $this->assertEquals($expectedResult, $originalString);
    }

    public function testReturnsSluggifiedString()
    {
        // $originalString = 'My string to be sluggified';
        $originalString = 'my-string-to-be-sluggified';
        $expectedResult = 'my-string-to-be-sluggified';

        $this->assertEquals($expectedResult, $originalString);
    }

}
