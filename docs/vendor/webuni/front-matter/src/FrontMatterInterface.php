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

/**
 * Interface FrontMatterInterface.
 */
interface FrontMatterInterface
{
    /**
     * Parse source.
     *
     * @param string|mixed $source The source
     *
     * @return Document
     */
    public function parse($source);

    /**
     * Dump document.
     *
     * @param Document $document The document
     *
     * @return string
     */
    public function dump(Document $document);
}
