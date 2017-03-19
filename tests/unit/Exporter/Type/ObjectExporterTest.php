<?php

namespace Exporter\Type;

use Dephpug\Exporter\Type\ObjectExporter;

class ObjectExporterTest extends \Codeception\Test\Unit
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
    public function testPrintValueWithAClass()
    {
        $message = <<<EOL
<?xml version="1.0" encoding="iso-8859-1"?><response><property name="\$klass" type="object" classname="stdClass"><property name="i" facet="public" type="int"><![CDATA[1]]></property></property></response>
EOL;

        $xml = simplexml_load_string($message);
        $objectExporter = new ObjectExporter();
        $response = $objectExporter->getExportedVar($xml);

        $this->assertEquals("{\n    \"i\": \"(int) `public` => 1\"\n}", $response);
    }
}
