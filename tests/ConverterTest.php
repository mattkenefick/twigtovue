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

/**
 * Lets you easily generate log records and a dummy
 * formatter for testing purposes
 */
class ConverterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests basic template
     *
     * @return void
     */
    public function testTemplateLoadedByConstructor()
    {
        $tags = $this->getTags();

        $converter = new Converter();
        $html    = $converter->twigToHtml($tags, $this->parser->template);
        $xml     = $converter->htmlToXml($html);
        $qp      = $converter->xmlToQueryPath($xml);
        $vueHtml = $converter->queryPathToVue($qp);

        $a = '<div class="second-loop" v-for="(model, index) of collection" v-bind:key="index">';
        $b = $vueHtml;

        $this->assertStringContainsString($a, $b);
    }

    /**
     * Tests full convert method
     *
     * @return void
     */
    public function testFullConvertHelperMethod()
    {
        $vueHtml = Converter::convert('data/kitchen-sink.twig');

        $a = '<div class="second-loop" v-for="(model, index) of collection" v-bind:key="index">';
        $b = $vueHtml;

        $this->assertStringContainsString($a, $b);
    }

    /**
     * Get tags
     *
     * @return array
     */
    private function getTags()
    {
        $this->parser = new Parser();
        // $this->parser->import('data/basic-loop-if.twig');
        $this->parser->import('data/basic-include.twig');
        $this->parser->import('data/kitchen-sink.twig');
        $tags = $this->parser->parse();

        return $tags;
    }
}
