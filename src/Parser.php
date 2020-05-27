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
 * Attemps to extract all tags, variables, and comments from a Twig string
 * like:
 *     {# (.*) #}
 *     {{ xyz(...) }}
 *     {% (.*) %}
 *     {{ xyz }}
 */
class Parser
{
    /**
     * Twig comments found
     *
     * @var array
     */
    public $comments;

    /**
     * Twig methods found
     *
     * @var array
     */
    public $methods;

    /**
     * Twig tags found
     *
     * @var array
     */
    public $tags;

    /**
     * Template to parse
     *
     * @var string
     */
    public $template;

    /**
     * Constructor
     *
     * @param string $template
     */
    public function __construct($template = '')
    {
        // Force html
        if (preg_match('/\.twig$/', $template)) {
            $this->import($template);
        }
        else {
            $this->setHtml($template);
        }
    }

    /**
     * Get source file
     *
     * @param  string $filename
     *
     * @return string
     */
    public function import(string $filename) : string
    {
        return $this->setHtml(file_get_contents($filename));
    }

    /**
     * Set markup
     *
     * @param  string $html
     *
     * @return string
     */
    public function setHtml(string $html) : string
    {
        return $this->template = $html;
    }

    /**
     * Parse Twig Tags
     *
     * @param  string
     * @return void
     */
    public function parse(): void
    {
        $this->parseComments();
        $this->parseMethods();
        $this->parseTags();
        $this->parseVariables();
    }

    /**
     * [parseComments description]
     * @return [type]
     */
    private function parseComments()
    {
        // s = dotall, . includes newlines
        // m = multiline, matches in more than first line
        // U = This modifier inverts the "greediness" of the quantifiers so that they are not greedy by default, but become greedy if followed by ?.
        $pattern = '#{\# (.*) \#}#Usm';
        $subject = $this->template;
        $matches;

        // Find all {# ... #} tags
        preg_match_all($pattern, $subject, $matches);

        $this->comments = $matches;
    }

    /**
     * [parseMethods description]
     * @return [type]
     */
    private function parseMethods()
    {
        // s = dotall, . includes newlines
        // m = multiline, matches in more than first line
        // U = This modifier inverts the "greediness" of the quantifiers so that they are not greedy by default, but become greedy if followed by ?.
        $pattern = '#{{ ([a-zA-Z0-9\_]+)\((.*)\) }}#Usm';
        $subject = $this->template;
        $matches;

        // Find all {{ xyz... }} tags
        preg_match_all($pattern, $subject, $matches);

        $this->methods = $matches;
    }

    /**
     * [parseTags description]
     * @return [type]
     */
    private function parseTags()
    {
        // s = dotall, . includes newlines
        // m = multiline, matches in more than first line
        // U = This modifier inverts the "greediness" of the quantifiers so that they are not greedy by default, but become greedy if followed by ?.
        $pattern = '#{% (.*) %}#Usm';
        $subject = $this->template;
        $matches;

        // Find all {% ... %} tags
        preg_match_all($pattern, $subject, $matches);

        $this->tags = $matches;
    }

    /**
     * [parseVariables description]
     * @return [type]
     */
    private function parseVariables()
    {
        // s = dotall, . includes newlines
        // m = multiline, matches in more than first line
        // U = This modifier inverts the "greediness" of the quantifiers so that they are not greedy by default, but become greedy if followed by ?.
        $pattern = '#{{ ([a-zA-Z0-9\_]+) }}#Usm';
        $subject = $this->template;
        $matches;

        // Find all {{ $... }} tags
        preg_match_all($pattern, $subject, $matches);

        $this->variables = $matches;
    }

}
