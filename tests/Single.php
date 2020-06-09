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
 * This test is used to isolate testing for better readability
 * during development.
 *
 * Change the code in our primary test to whatever you're working
 * on and run:
 *
 * composer phpunit tests/Single.php
 */
class Single extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     */
    public function testPrimary()
    {
        // $vueHtml = Converter::convert('<div>{% include "namespace" ~ "ok.twig" %}</div>');
        // $vueHtml = Converter::convert('<div>{# Sup Homie #}</div>');
        $vueHtml = Converter::convert('data/pagination.twig');
        // $vueHtml = Converter::convert('data/basic-loop.twig');
        // $vueHtml = Converter::convert('data/include-objects.twig');
        // $vueHtml = Converter::convert('data/basic-if-else.twig');
        // $vueHtml = Converter::convert('data/real-if-else.twig');
        // $vueHtml = Converter::convert('data/kitchen-sink-2.twig');
        // $vueHtml = Converter::convert('data/footer.twig');

        $a = '<!-- Test Comment -->';
        $b = $vueHtml;

        $message = "\n ========================================================== ";
        $message .= "\n\n";
        $message .= $vueHtml;
        $message .= "\n\n";
        $message .= ' ========================================================== ';

        throw new \Exception($message);

        // $this->assertStringContainsString($a, $b);
    }
}
