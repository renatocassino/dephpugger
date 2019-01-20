<?php

namespace Exporter\Type;

class ObjectExporterTest extends \PHPUnit\Framework\TestCase
{
    public function testPrintValueWithAClass()
    {
        $message = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?><response><property name="$klass" type="object" classname="stdClass"><property name="i" facet="public" type="int"><![CDATA[1]]></property></property></response>
EOL;

        $response = <<<'EOL'
<?xml version="1.0" encoding="UTF-8"?>
<response>
  <property><![CDATA[b2JqZWN0KGNvbnRlbnQgaGVyZSk=]]></property>
</response>
EOL;

        $xml = simplexml_load_string($message);
        $objectExporter = $this->getMockBuilder('\Dephpug\Exporter\Type\ObjectExporter')
            ->setMethods(['getResponseByCommand'])
            ->getMock();

        $objectExporter->method('getResponseByCommand')
            ->willReturn($response);

        $response = $objectExporter->getExportedVar($xml);

        $this->assertEquals('object(content here)', $response);
    }
}
