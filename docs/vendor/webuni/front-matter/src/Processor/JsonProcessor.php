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
 * The JSON processor.
 *
 * @author Martin Hasoň <martin.hason@gmail.com>
 */
class JsonProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse($string)
    {
        return json_decode($string);
    }

    /**
     * {@inheritdoc}
     */
    public function dump($data)
    {
        if (is_array($data) && empty($data)) {
            return '';
        }

        return json_encode($data);
    }
}
