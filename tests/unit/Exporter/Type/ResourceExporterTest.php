<?php

namespace Exporter\Type;

use Dephpug\Exporter\Type\ResourceExporter;

class ResourceExporterTest extends \PHPUnit\Framework\TestCase
{
    public function testPrintValueWithResource()
    {
        $message = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response><property name="$fp" type="resource"><![CDATA[resource id='6' type='stream']]></property></response>
EOL;
        $xml = simplexml_load_string($message);
        $resourceExporter = new ResourceExporter();
        $response = $resourceExporter->getExportedVar($xml);
        $this->assertEquals('[resource id=\'6\' type=\'stream\']', $response);
    }
}
