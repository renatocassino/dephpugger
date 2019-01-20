<?php

namespace Dephpug\Parser;

use Dephpug\MessageEvent as MessageParse;
use Dephpug\Exporter\Exporter;
use Dephpug\Output;

/**
 * Event to get a property calling the respective type
 *
 * @example <?xml version="1.0" encoding="iso-8859-1"?>
 *  <response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="property_get" transaction_id="5"><property name="$d" fullname="$d" type="float"><![CDATA[3.3333333333333]]></property></response>
 */
class PropertyGetMessageEvent extends MessageParse
{
    /**
     * Exporter to print variable content
     */
    public $exporter;

    public function __construct()
    {
        $this->exporter = new Exporter();
    }

    /**
     * Trying match with an exporter
     *
     * @param  string $xml
     * @return void
     */
    public function match(string $xml)
    {
        $this->exporter->setXml($xml);

        return $this->exporter->isContentToPrint();
    }

    /**
     * Print the content
     *
     * @return void
     */
    public function exec()
    {
        Output::print($this->exporter->printByXml());
    }
}
