<?php

namespace Exporter\Type;

use Dephpug\Exporter\Type\StringExporter;

class StringExporterTest extends \PHPUnit\Framework\TestCase
{
    public function testPrintValueWithAString()
    {
        $message = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response><property name="$str" type="string"><![CDATA[TXkgU3RyaW5n]]></property></response>
EOL;
        $xml = simplexml_load_string($message);
        $stringExporter = new StringExporter();
        $response = $stringExporter->getExportedVar($xml);

        $this->assertEquals('My String', $response);
    }
}
