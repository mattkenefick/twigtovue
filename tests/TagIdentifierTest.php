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

use PolymerMallard\TwigToVue\Converter;
use PolymerMallard\TwigToVue\Parser;
use PolymerMallard\TwigToVue\TagIdentifier;

/**
 * Lets you easily generate log records and a dummy
 * formatter for testing purposes
 */
class TagIdentifierTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests tag identifier
     *
     * @return void
     */
    public function testIdentifyTag()
    {
        $text = TagIdentifier::identify('{% if foo %}');

        $this->assertEquals('if', $text);
    }

    /**
     * Tests tag identifier
     *
     * @return void
     */
    public function testReplace()
    {
        $text = TagIdentifier::replace('if foo');

        $this->assertEquals('foo', $text);
    }

}