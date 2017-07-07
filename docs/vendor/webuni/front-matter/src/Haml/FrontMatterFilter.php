<?php

/*
 * This is part of the webuni/front-matter package.
 *
 * (c) Martin Hasoň <martin.hason@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webuni\FrontMatter\Haml;

use MtHaml\Filter\FilterInterface;
use MtHaml\Node\Filter;
use MtHaml\NodeVisitor\RendererAbstract;
use Webuni\FrontMatter\FrontMatterInterface;

/**
 * @author Martin Hasoň <martin.hason@gmail.com>
 */
class FrontMatterFilter implements FilterInterface
{
    private $parser;
    private $filter;

    public function __construct(FrontMatterInterface $parser, FilterInterface $filter)
    {
        $this->parser = $parser;
        $this->filter = $filter;
    }

    /**
     * {@inheritdoc}
     */
    public function isOptimizable(RendererAbstract $renderer, Filter $node, $options)
    {
        return $this->filter->isOptimizable($renderer, $node, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function optimize(RendererAbstract $renderer, Filter $node, $options)
    {
        return $this->filter->optimize($renderer, $node, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function filter($content, array $context, $options)
    {
        $document = $this->parser->parse($content);
        $document->setContent($this->filter->filter($document, $context, $options));

        return $document;
    }
}
