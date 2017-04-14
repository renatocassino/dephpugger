<?php

namespace Dephpug\Parser;

use Dephpug\MessageEvent as MessageParse;
use Dephpug\MessageParse as MessageParser;
use Dephpug\Output;

class VerboseModeMessageParse extends MessageParse
{
    public function match(string $xml)
    {
        if ($this->core->config->debugger['verboseMode']) {
            $messageParser = new MessageParser();
            $xml = $messageParser->xmlBeautifier($xml);
            try {
                Output::print("\n<comment>{$xml}</comment>\n");
            } catch (\Exception $e) {
                echo $xml;
            }
        }
    }

    public function exec()
    {
        $this->core->dbgpServer->sendCommand('run -i 1');
    }
}
