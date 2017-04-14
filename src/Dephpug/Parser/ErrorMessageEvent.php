<?php

namespace Dephpug\Parser;

use Dephpug\MessageEvent as MessageParse;
use Dephpug\Output;

class ErrorMessageEvent extends MessageParse
{
    public $message;
    public $code;

    public function match(string $xml)
    {
        $xml = @simplexml_load_string($xml);
        if (isset($xml->error)) {
            $this->message = (string) $xml->error->message;
            $this->code = $xml->error['code'];

            return true;
        }

        return false;
    }

    public function exec()
    {
        Output::print("<fg=red;options=bold>Code: {$this->code} - {$this->message}</>");
    }
}
