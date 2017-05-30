<?php

namespace Exporter\Type;

use Dephpug\Exporter\Type\FloatExporter;

class FloatExplorerTest extends \PHPUnit\Framework\TestCase
{
    public function testPrintValueWithAFloat()
    {
        $message = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response><property name="$f" type="float"><![CDATA[3.141]]></property></response>
EOL;
        $xml = simplexml_load_string($message);
        $floatExporter = new FloatExporter();
        $response = $floatExporter->getExportedVar($xml);
        $this->assertEquals('3.141', $response);
    }
}
