<?php

namespace Dephpug\Parse;

use Dephpug\MessageEvent as MessageParse;

class InitMessageEvent extends MessageParse
{
    public function match(string $xml)
    {
        echo $xml;
        return preg_match('/init/', $xml);
    }

    public function exec()
    {
        $this->core->dbgpServer->sendCommand('run -i 1');
    }
}
