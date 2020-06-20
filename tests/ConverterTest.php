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
        $parser = $this->getTags();

        $converter = new Converter();
        $html    = $converter->twigToHtml($parser, $parser->template);
        $xml     = $converter->htmlToXml($html);
        $qp      = $converter->xmlToQueryPath($xml);
        $vueHtml = $converter->queryPathToVue($qp);

        $a = '<div class="second-loop" v-for="(model, index) of collection" v-bind:key="index">';
        $b = $vueHtml;

        $this->assertStringContainsString($a, $b);
    }

    /**
     * @return void
     */
    public function testComments()
    {
        $vueHtml = Converter::convert('data/basic-comments.twig');

        $a = '<!-- Test Comment -->';
        $b = $vueHtml;

        $this->assertStringContainsString($a, $b);
    }

    /**
     * @return void
     */
    public function testCommentsByString()
    {
        $vueHtml = Converter::convert('<div>{# Test Two #}</div>');

        $a = '<div><!-- Test Two --></div>';
        $b = $vueHtml;

        $this->assertStringContainsString($a, $b);
    }

    /**
     * @return void
     */
    public function testElseIf()
    {
        $vueHtml = Converter::convert('data/basic-if-else.twig');

        $a = '<div v-else-if="something == \'something\'">';
        $b = $vueHtml;

        $this->assertStringContainsString($a, $b);

        $a = '<p v-else="">';
        $b = $vueHtml;

        $this->assertStringContainsString($a, $b);

        $a = '<h3 v-if="foo">';
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
     * @todo
     *
     * @return void
     */
    public function testIncludeLiteralsOnly()
    {
        $vueHtml = Converter::convert('data/include-objects.twig');

        $this->assertStringContainsString(' :header="\'Literal String\'', $vueHtml);
        $this->assertStringContainsString(' :integer="5"', $vueHtml);
    }

    /**
     * @todo
     *
     * @return void
     */
    public function testMultipleAttributes()
    {
        $vueHtml = Converter::convert('data/multiple-attributes.twig');

        $a = '<a :href="header.href" :title="header.text">';
        $b = $vueHtml;

        $this->assertStringContainsString($a, $b);
    }

    /**
     * Tests full convert method
     *
     * @return void
     */
    public function testNamespacedIncludes()
    {
        $vueHtml = Converter::convert('data/kitchen-sink.twig');

        $a1 = '<HeaderMain';
        $b1 = $vueHtml;

        $a2 = '<ViewFooterMain';
        $b2 = $vueHtml;

        $this->assertStringContainsString($a1, $b1);
        $this->assertStringContainsString($a2, $b2);
    }

    /**
     * Tests automatical remove of duplicate ending names that could be
     * caused by C# style patterns such as, View/Film/Index/Index.twig
     * should be ViewFilmIndex
     *
     * @return void
     */
    public function testSequentialDuplicateEndings()
    {
        $vueHtml = Converter::convert('data/kitchen-sink.twig');

        $a = '<ViewFilmIndex :films="[]"></ViewFilmIndex>';
        $b = $vueHtml;

        $this->assertStringContainsString($a, $b);
    }

    /**
     * Get tags
     *
     * @return array
     */
    private function getTags() : Parser
    {
        $this->parser = new Parser();
        // $this->parser->import('data/basic-loop-if.twig');
        $this->parser->import('data/basic-include.twig');
        $this->parser->import('data/kitchen-sink.twig');
        $this->parser->parse();

        return $this->parser;
    }
}
