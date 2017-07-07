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

use Symfony\Component\Yaml\Yaml;

/**
 * The YAML processor.
 *
 * @author Martin Hasoň <martin.hason@gmail.com>
 */
class YamlProcessor implements ProcessorInterface
{
    private $yaml;

    public function __construct(Yaml $yaml = null)
    {
        $this->yaml = $yaml ?: new Yaml();
    }

    /**
     * {@inheritdoc}
     */
    public function parse($string)
    {
        return $this->yaml->parse($string);
    }

    /**
     * {@inheritdoc}
     */
    public function dump($data)
    {
        if (is_array($data) && empty($data)) {
            return '';
        }

        return $this->yaml->dump($data);
    }
}
