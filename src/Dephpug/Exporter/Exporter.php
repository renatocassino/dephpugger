<?php

namespace Dephpug\Exporter;

class Exporter
{
    private $xml;

    public function setXml($xml)
    {
        $this->xml = @simplexml_load_string($xml);
    }

    public function printByXml()
    {
        if (!$this->isContentToPrint()) {
            return null;
        }

        $typeVar = base64_decode((string) $this->xml->property->children()[0]);
        $content = base64_decode((string) $this->xml->property->children()[1]);
        return " => ({$typeVar}) {$content}";
    }

    public function isContentToPrint()
    {
        $command = (string) $this->xml['command'];

        return 'eval' === $command || 'property_get' === $command;
    }
}
