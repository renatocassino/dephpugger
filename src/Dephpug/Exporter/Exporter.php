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

        switch ($typeVar) {
        case 'int':
            $klass = Type\IntegerExporter::class;
            break;
        case 'float':
            $klass = Type\FloatExporter::class;
            break;
        case 'null':
            $klass = Type\NullExporter::class;
            break;
        case 'bool':
            $klass = Type\BoolExporter::class;
            break;
        case 'string':
            $klass = Type\StringExporter::class;
            break;
        case 'array':
            $klass = Type\ArrayExporter::class;
            break;
        case 'object':
            $klass = Type\ObjectExporter::class;
            break;
        case 'resource':
            $klass = Type\ResourceExporter::class;
            break;
        default:
            $klass = Type\UnknownExporter::class;
            break;
        }

        return $klass;
    }
}
