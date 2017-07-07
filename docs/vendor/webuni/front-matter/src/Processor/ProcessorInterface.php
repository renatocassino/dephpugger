<?php

/*
 * This is part of the webuni/front-matter package.
 *
 * (c) Martin Hasoň <martin.hason@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webuni\FrontMatter\Processor;

/**
 * @author Martin Hasoň <martin.hason@gmail.com>
 */
interface ProcessorInterface
{
    /**
     * Parses front matter string into a data.
     *
     * @param string $string The string
     *
     * @return mixed
     */
    public function parse($string);

    /**
     * Dumps a data to a string.
     *
     * @param mixed $data The data
     *
     * @return string
     */
    public function dump($data);
}
