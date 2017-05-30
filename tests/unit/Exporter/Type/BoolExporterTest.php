<?php

namespace Exporter\Type;

use Dephpug\Exporter\Type\BoolExporter;

class BoolExporterTest extends \PHPUnit\Framework\TestCase
{
    public function testGettingBoolVariableWhenTrue()
    {
        $message = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response><property name="$i" type="bool"><![CDATA[1]]></property></response>
EOL;
        $xml = simplexml_load_string($message);
        $boolExporter = new BoolExporter();
        $response = $boolExporter->getExportedVar($xml);
        $this->assertEquals('true', $response);
    }

    public function testGettingBoolVariableWhenFalse()
    {
        $message = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response><property name="$i" type="bool"><![CDATA[0]]></property></response>
EOL;
        $xml = simplexml_load_string($message);
        $boolExporter = new BoolExporter();
        $response = $boolExporter->getExportedVar($xml);
        $this->assertEquals('false', $response);
    }
}
