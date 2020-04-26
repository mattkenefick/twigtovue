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
class ParserTest extends \PHPUnit\Framework\TestCase
{

    public function testFoo()
    {
        $actualValue = Parser::foo(2);
        $expectedValue = 1;

        $this->assertEquals($actualValue, $expectedValue);
    }

}
