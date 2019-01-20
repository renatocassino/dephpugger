<?php

namespace Exporter\Type;

class ArrayExporterTest extends \PHPUnit\Framework\TestCase
{
    public function testMixArray()
    {
        // Example of response
        $message = <<<'EOL'
<?xml version="1.0" encoding="UTF-8"?>
<response>
   <property>
      <property name="0" type="int"><![CDATA[0]]></property>
   </property>
</response>
EOL;

        $response = <<<'EOL'
<?xml version="1.0" encoding="UTF-8"?>
<response>
  <property><![CDATA[YXJyYXkoY29udGVudCBoZXJlKQ==]]></property>
</response>
EOL;

        $xml = simplexml_load_string($message);
        $arrayExporter = $this->getMockBuilder('\Dephpug\Exporter\Type\ArrayExporter')
            ->setMethods(['getResponseByCommand'])
            ->getMock();

        $arrayExporter->method('getResponseByCommand')
            ->willReturn($response);

        $response = $arrayExporter->getExportedVar($xml);
        $this->assertEquals('array(content here)', $response);
    }
}
