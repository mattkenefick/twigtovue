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
 *
 * The `tags` class represents converters based on found tags
 * in the templates.
 *
 * The `convert` method parses templates into comments, methods,
 * tags, and variables then uses those to convert it to a Vue template.
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
     * Converts a Twig file to VueJS based on filepath or template
     *
     * @param  string $filepathOrTemplate
     *
     * @return string
     */
    public static function convert(string $filepathOrTemplate): string
    {
        // Parse out comments, methods, tags, and variables
        $parser = new Parser($filepathOrTemplate);
        $parser->parse();

        // Create instance of Converter
        $instance = new self();

        // Convert
        // @todo, do we need to pass in everything from parser?
        $html = $instance->twigToHtml($parser, $parser->template);
// echo $html;exit;

        // Convert our HTML tags into generic XML which helps
        // us find closing tags
        $xml = $instance->htmlToXml($html);
// print_r($xml);exit;

        // Use QueryPath to traverse through XML easier
        $qp = $instance->xmlToQueryPath($xml);
// echo $qp->html();exit;

        // Convert our new XML into a Vue template
        $vueHtml = $instance->queryPathToVue($qp);

        return $vueHtml;
    }

    /**
     * Convert
     *
     * The outer array [0] has tags {% foo %}
     * The inner array [1] has no tags: foo
     *
     * @param  Parser $parser
     *
     * @return void
     */
    public function twigToHtml(Parser $parser, string $template = ''): string
    {
        $tags = $parser->tags;

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

            // Run convert on tag class
            $html = $class
                ? $class::convert($html, $tag, $outerValue, $params)
                : $html;
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
        // @todo, This order was reversed before. Do we need to flip it back?
        $html = html_entity_decode($html, ENT_QUOTES, 'utf-8');
        $html = self::xmlEscape($html);

        // Replace tag openings inside quotes
        $html = preg_replace('#(?<=")([^"]+)( < )([^"]+)(?=")#is', '$1 &lt; $3', $html);
        $html = preg_replace('#(?<=")([^"]+)( > )([^"]+)(?=")#is', '$1 &gt; $3', $html);

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

        $html = XmlToVue\ConvertLoops::convert($queryPath);

        $html = XmlToVue\ConvertIncludes::convert($queryPath);

        $html = XmlToVue\ConvertConditionals::convert($queryPath);

        $html = $this->cleanup($queryPath->html() ?: '<!-- Failed to parse in QueryPath -->');

        return $html;
    }

    /**
     * Post HTML work after converting things
     *
     * @param  string $html
     * @return string
     */
    private function cleanup(string $html): string
    {
        // Typically this is used for conditionals and shouldn't be encoded
        $html = str_replace('&amp;&amp;', '&&', $html);

        // We want "&gt;" as literal within conditional attributes. It's not
        // standard XML, but it's used for templates.
        // @todo, we should regex here to check for quote wrappers
        $html = str_replace(' &gt; ', ' > ', $html);
        $html = str_replace(' &lt; ', ' < ', $html);

        return $html;
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

    /**
     * Escape ampersands and others for the htmlToXml function
     *
     * @param  string $string
     *
     * @return string
     */
    private static function xmlEscape(string $string) : string
    {
        return str_replace(
            array('&'),
            array('&amp;'),
            $string
        );
    }
}
