<?php

namespace Dephpug\Parser;

use Dephpug\MessageEvent as MessageParse;

class CloseMessageEvent extends MessageParse
{
    public function match(string $xml)
    {
        return (bool) preg_match('/status=\"stopp(?:ed|ing)\"/', $xml);
    }

    public function exec()
    {
        throw new \Dephpug\Exception\QuitException('Closing request');
    }
}
