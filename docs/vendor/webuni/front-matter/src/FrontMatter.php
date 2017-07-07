<?php

/*
 * This is part of the webuni/front-matter package.
 *
 * (c) Martin Hasoň <martin.hason@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Webuni\FrontMatter;

use Webuni\FrontMatter\Processor\ProcessorInterface;
use Webuni\FrontMatter\Processor\YamlProcessor;

class FrontMatter implements FrontMatterInterface
{
    private $startSep;
    private $endSep;
    private $processor;
    private $regexp;

    public function __construct(ProcessorInterface $processor = null, $startSep = '---', $endSep = '---')
    {
        $this->startSep = $startSep;
        $this->endSep = $endSep;
        $this->processor = $processor ?: new YamlProcessor();

        $this->regexp = '{^(?:'.preg_quote($startSep).")[\r\n|\n]*(.*?)[\r\n|\n]+(?:".preg_quote($endSep).")[\r\n|\n]*(.*)$}s";
    }

    /**
     * {@inheritdoc}
     */
    public function parse($source)
    {
        if (preg_match($this->regexp, $source, $matches) === 1) {
            $data = '' !== trim($matches[1]) ? $this->processor->parse(trim($matches[1])) : [];

            return new Document($matches[2], $data);
        }

        return new Document($source);
    }

    /**
     * {@inheritdoc}
     */
    public function dump(Document $document)
    {
        $data = trim($this->processor->dump($document->getData()));
        if ('' === $data) {
            return $document->getContent();
        }

        return sprintf("%s\n%s\n%s\n%s", $this->startSep, $data, $this->endSep, $document->getContent());
    }
}
