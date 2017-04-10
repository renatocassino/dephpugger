<?php

namespace Dephpug\Parse;

use Dephpug\MessageEvent as MessageParse;
use Dephpug\MessageParse as MessageParser;
use Dephpug\Output;

class VerboseModeMessageEvent extends MessageParse
{
    public function match(string $xml)
    {
        if($this->core->config->debugger['verboseMode']) {
            $messageParser = new MessageParser();
            $xml = $messageParser->xmlBeautifier($xml);
            Output::print("\n<comment>{$xml}</comment>\n");
        }
    }

    public function exec()
    {
        $this->core->dbgpServer->sendCommand('run -i 1');
    }
}
