<?php

/*
 * This is part of the webuni/front-matter package.
 *
 * (c) Martin Hasoň <martin.hason@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webuni\FrontMatter\Twig;

use Webuni\FrontMatter\FrontMatterInterface;

/**
 * @author Martin Hasoň <martin.hason@gmail.com>
 */
class FrontMatterLoader implements \Twig_LoaderInterface, \Twig_ExistsLoaderInterface
{
    private $loader;
    private $parser;

    public function __construct(FrontMatterInterface $parser, \Twig_LoaderInterface $loader)
    {
        $this->loader = $loader;
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function getSource($name)
    {
        $source = $this->loader->getSource($name);

        return $this->parser->parse($source, ['filename' => $name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheKey($name)
    {
        return $this->loader->getCacheKey($name);
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh($name, $time)
    {
        return $this->loader->isFresh($name, $time);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($name)
    {
        if ($this->loader instanceof \Twig_ExistsLoaderInterface) {
            return $this->loader->exists($name);
        } else {
            try {
                $this->loader->getSource($name);

                return true;
            } catch (\Twig_Error_Loader $e) {
                return false;
            }
        }
    }
}
