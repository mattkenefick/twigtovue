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
    /**
     * Tests basic template
     *
     * @return void
     */
    public function testTemplateLoadedByConstructor()
    {
        $parser = new Parser('<div></div>');
        $text = $parser->template;

        $this->assertStringContainsString('<div></div>', $text);
    }

    /**
     * Tests importing a file
     *
     * @return void
     */
    public function testTemplateImportedByFile()
    {
        $parser = new Parser();
        $text = $parser->import('data/basic-if.twig');

        $this->assertStringContainsString('if foo', $text);
    }

    /**
     * Tests parsed tags
     *
     * @return void
     */
    public function testFindTwigTagsInBasicTemplate()
    {
        $parser = new Parser();
        $parser->import('data/basic-if.twig');
        $tags = $parser->parse();

        $this->assertCount(2, $tags);
    }
}
