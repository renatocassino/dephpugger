<?php

namespace Dephpug\Parser;

use Dephpug\MessageEvent as MessageParse;
use Dephpug\Exporter\Exporter;
use Dephpug\Output;

class PropertyGetMessageEvent extends MessageParse
{
    private $exporter;

    public function __construct()
    {
        $this->exporter = new Exporter();
    }

    public function match(string $xml)
    {
        $this->exporter->setXml($xml);

        return $this->exporter->isContentToPrint();
    }

    public function exec()
    {
        Output::print($this->exporter->printByXml());
    }
}
