<?php

namespace Dephpug\Exporter;

class Exporter
{
    private $xml;

    private $types = [
        'int' => Type\IntegerExporter::class,
        'float' => Type\FloatExporter::class,
        'null' => Type\NullExporter::class,
        'bool' => Type\BoolExporter::class,
        'string' => Type\StringExporter::class,
        'array' => Type\ArrayExporter::class,
        'object' => Type\ObjectExporter::class,
        'resource' =>Type\ResourceExporter::class,
    ];

    public function setXml($xml)
    {
        $this->xml = @simplexml_load_string($xml);
    }

    public function printByXml()
    {
        if (!$this->isContentToPrint()) {
            return null;
        }

        $klassName = $this->getClassExporter();
        $klass = new $klassName();

        return $this->printByClass($klass);
    }

    public function printByClass(iExporter $klass)
    {
        $content = $klass->getExportedVar($this->xml);

        return " => {$content}\n\n";
    }

    public function isContentToPrint()
    {
        $command = (string) $this->xml['command'];

        return 'eval' === $command || 'property_get' === $command;
    }

    private function getClassExporter()
    {
        // Getting value
        $typeVar = (string) $this->xml->property['type'];

        if (isset($this->types[$typeVar])) {
            return $this->types[$typeVar];
        }

        return  Type\UnknownExporter::class;
    }
}
