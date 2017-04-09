<?php

namespace Dephpug\Parse;

use Dephpug\MessageEvent as MessageParse;

class CloseMessageEvent extends MessageParse
{
    public function match(string $xml)
    {
        return preg_match('/status=\"stopping\"/', $xml);
    }

    public function exec()
    {
        throw new \Dephpug\Exception\QuitException('Closing request');
    }
}
