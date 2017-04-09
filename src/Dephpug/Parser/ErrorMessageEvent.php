<?php

namespace Dephpug\Parse;

use Dephpug\MessageEvent as MessageParse;

class ErrorMessageEvent extends MessageParse
{
    public $message;
    public $code;

    public function match(string $xml)
    {
        $xml = simplexml_load_string($message);
        if (isset($xml->error)) {
            $this->message = (string) $xml->error->message;
            $this->code = $xml->error['code'];
            return true;
        }

        return false;
    }

    public function exec()
    {
        Ouput::print("<fg=red;options=bold>Code: {$this->code} - {$this->message}</>");
    }
}
