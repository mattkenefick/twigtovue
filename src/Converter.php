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

use PolymerMallard\TwigToVue\Convert\XmlToVue;

/**
 * Converter
 */
class Converter
{

    /**
     * HTML
     *
     * @var string
     */
    public $html;

    /**
     * QueryPath / DOMParser
     *
     * @var QueryPath
     */
    public $qp;

    /**
     * XML
     *
     * @var XMLElement
     */
    public $xml;

    /**
     * HTML tags
     *
     * Value can have `tag` to return or
     * we can `convert` it to something else
     *
     * @var array
     */
    private $tags = [
        'for'     => 'PolymerMallard\TwigToVue\Convert\TwigToXml\ConvertFor',
        'endfor'  => 'PolymerMallard\TwigToVue\Convert\TwigToXml\ConvertFor',
        'include' => 'PolymerMallard\TwigToVue\Convert\TwigToXml\ConvertInclude',
        'if'      => 'PolymerMallard\TwigToVue\Convert\TwigToXml\ConvertIf',
        'elseif'  => 'PolymerMallard\TwigToVue\Convert\TwigToXml\ConvertIf',
        'else'    => 'PolymerMallard\TwigToVue\Convert\TwigToXml\ConvertIf',
        'endif'   => 'PolymerMallard\TwigToVue\Convert\TwigToXml\ConvertIf',
    ];

    /**
     * Converts a Twig file to VueJS based on filepath
     *
     * @param  string $filepath
     *
     * @return string
     */
    public static function convert(string $filepath) : string
    {
        $parser = new Parser();
        $parser->import($filepath);
        $tags = $parser->parse();

        $instance = new self();

        // Convert
        $html = $instance->twigToHtml($tags, $parser->template);
        $xml = $instance->htmlToXml($html);
        $qp = $instance->xmlToQueryPath($xml);
        $vueHtml = $instance->queryPathToVue($qp);

        return $vueHtml;
    }

    /**
     * Convert
     *
     * The outer array [0] has tags {% foo %}
     * The inner array [1] has no tags: foo
     *
     * @param  array $tags
     *
     * @return void
     */
    public function twigToHtml(array $tags, string $template = '')
    {
        $outerItems = $tags[0];
        $innerItems = $tags[1];
        $html = $template;

        // Loop through found tags like {% for ... %} and
        // convert them to HTML elements
        foreach ($outerItems as $index => $outerValue) {
            $innerValue = $innerItems[$index];

            // Get tag as identified
            $tag = TagIdentifier::identify($innerValue);
            $params = TagIdentifier::replace($innerValue);

            // Convert tags to HTML elements
            $class = $this->tags[$tag];
            $html = $class::convert($html, $tag, $outerValue, $params);
        }

        // Convert comments
        $html = $this->fixComments($html);

        return $this->html = $html;
    }

    /**
     * Convert HTML to XML
     *
     * @param  string $html
     *
     * @return object
     */
    public function htmlToXml(string $html)
    {
        $html = html_entity_decode($html, ENT_QUOTES, 'utf-8');

        return $this->xml = @simplexml_load_string($html, 'SimpleXMLElement', LIBXML_NOENT);
    }

    /**
     * Convert XML to QP
     *
     * @param  object $xml
     *
     * @return object
     */
    public function xmlToQueryPath($xml)
    {
        return $this->qp = qp($xml ?? $this->xml);
    }

    /**
     * Query Path to VueJS
     *
     * @param  string $queryPath
     *
     * @return string
     */
    public function queryPathToVue(object $queryPath) : string
    {
        $html = XmlToVue\ConvertAttributes::convert($queryPath);

        $html = XmlToVue\ConvertConditionals::convert($queryPath);

        $html = XmlToVue\ConvertLoops::convert($queryPath);

        $html = XmlToVue\ConvertIncludes::convert($queryPath);

        return $queryPath->html();
    }

    /**
     * Replace comments
     *
     * @param  string $value
     *
     * @return string
     */
    private static function fixComments(string $value) : string
    {
        $value = str_replace('{#', '<!--', $value);
        $value = str_replace('#}', '-->', $value);
        $value = trim($value);
        return $value;
    }

}
