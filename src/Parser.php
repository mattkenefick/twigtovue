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
 * ---
 */
class Parser
{
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
     * Test function
     *
     * @return int Test integer
     */
    public static function foo(int $arbitrary): int
    {
        return 1;
    }

    /**
     * Constructor
     *
     * @param string $template
     */
    public function __construct($template = '')
    {
        $this->template = $template;
    }

    /**
     * Get source file
     *
     * @param  string $filename
     *
     * @return string
     */
    public function import($filename) : string
    {
        return $this->template = file_get_contents($filename);
    }

    /**
     * Parse Twig Tags
     *
     * @param  string
     * @return void
     */
    public function parse()
    {
        // s = dotall, . includes newlines
        // m = multiline, matches in more than first line
        // U = This modifier inverts the "greediness" of the quantifiers so that they are not greedy by default, but become greedy if followed by ?.
        $pattern = '#{% (.*) %}#Usm';
        $subject = $this->template;
        $matches;

        // Find all {% ... %} tags
        preg_match_all($pattern, $subject, $matches);

        return $this->tags = $matches;
    }

}
