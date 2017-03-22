<?php

namespace Exporter\Type;

use Dephpug\Exporter\Type\ArrayExporter;

class ArrayExporterTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function __testMixArray()
    {
        // Example of response
        $message = <<<'EOL'
<?xml version="1.0" encoding="UTF-8"?>
<response>
   <property>
      <property name="0" type="int"><![CDATA[0]]></property>
      <property name="1" type="int"><![CDATA[1]]></property>
      <property name="2" type="int"><![CDATA[2]]></property>
      <property name="3" type="int"><![CDATA[3]]></property>
      <property name="4" type="int"><![CDATA[4]]></property>
      <property name="5" type="int"><![CDATA[5]]></property>
      <property name="6" type="int"><![CDATA[6]]></property>
      <property name="7" type="int"><![CDATA[7]]></property>
      <property name="8" type="int"><![CDATA[8]]></property>
      <property name="9" type="int"><![CDATA[9]]></property>
      <property name="withString" type="string"><![CDATA[d2l0aFN0cmluZw==]]></property>
      <property name="numbers" type="array" children="1" numchildren="3" />
      <property name="children" type="array" children="1" numchildren="2" />
   </property>
</response>
EOL;
        $xml = simplexml_load_string($message);
        $arrayExporter = new ArrayExporter();
        $response = $arrayExporter->getExportedVar($xml);

        $jsonPrettyFormat = json_encode(['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'withString' => 'withString', 'numbers' => '(array) [...]', 'children' => '(array) [...]'], JSON_PRETTY_PRINT);

        $this->assertEquals("\n{$jsonPrettyFormat}", $response);
    }
}
