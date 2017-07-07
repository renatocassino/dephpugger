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
 * Class Document.
 */
class Document
{
    private $content;
    private $data;

    public function __construct($content, $data = [])
    {
        $this->content = $content;
        $this->data = $data;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getDataWithContent($key = '__content')
    {
        return array_merge($this->data, [$key => $this->content]);
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function __toString()
    {
        return (string) $this->content;
    }
}
