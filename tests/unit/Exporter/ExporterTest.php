<?php

namespace Exporter;

class ExporterTest extends \Codeception\Test\Unit
{
    protected $arrayXml;
    protected $boolXml;
    protected $floatXml;
    protected $integerXml;
    protected $nullXml;
    protected $objectXml;
    protected $resourceXml;
    protected $stringXml;
    protected $unknowXml;
    protected $xmlToNotPrint;
    protected $xmlToPrintWithEval;

    protected function _before()
    {
        $this->arrayXml = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response command="property_get"><property name="\$i" type="array">...</property></response>
EOL;

        $this->boolXml = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response command="property_get"><property name="\$i" type="bool"><![CDATA[1]]></property></response>
EOL;

        $this->floatXml = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response command="property_get"><property name="\$i" type="float"><![CDATA[3.4]]></property></response>
EOL;

        $this->integerXml = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response command="property_get"><property name="\$i" type="int"><![CDATA[1]]></property></response>
EOL;

        $this->nullXml = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response command="property_get"><property name="\$i" type="null"></property></response>
EOL;

        $this->objectXml = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response command="property_get"><property name="\$i" type="object"></property></response>
EOL;

        $this->resourceXml = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response command="property_get"><property name="\$i" type="resource"></property></response>
EOL;

        $this->stringXml = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response command="property_get"><property name="\$i" type="string">TXkgU3RyaW5n</property></response>
EOL;

        $this->unknowXml = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response command="property_get"><property name="\$i" type="new_type_in_php"></property></response>
EOL;

        $this->xmlToNotPrint = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response command="property_set"><property name="$i" type="new_type_in_php"></property></response>
EOL;

        $this->xmlToPrintWithEval = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response command="eval"><property name="$i" type="float">3.14</property></response>
EOL;
    }

    protected function _after()
    {
    }

    public function testPrintByClass()
    {
        $exporter = new \Dephpug\Exporter\Exporter();
        $exporter->setXml($this->stringXml);
        $this->assertEquals(" => My String\n\n", $exporter->printByXml());
    }

    public function testIfIsContentToPrint()
    {
        $exporter = new \Dephpug\Exporter\Exporter();
        $exporter->setXml($this->stringXml);
        $this->assertTrue($exporter->isContentToPrint());
    }

    public function testIfIsContentToNotPrint()
    {
        $exporter = new \Dephpug\Exporter\Exporter();
        $exporter->setXml($this->xmlToNotPrint);
        $this->assertFalse($exporter->isContentToPrint());
    }

    public function testIfIsContentToPrintWithEval()
    {
        $exporter = new \Dephpug\Exporter\Exporter();
        $exporter->setXml($this->xmlToPrintWithEval);
        $this->assertTrue($exporter->isContentToPrint());
    }

    public function testGetArrayClass()
    {
        $stub = $this->getMockBuilder('\Dephpug\Exporter\Exporter')
              ->setMethods(['printByClass'])
              ->getMock();

        $stub->expects($this->once())
            ->method('printByClass')
            ->with(new \Dephpug\Exporter\Type\ArrayExporter());

        $stub->setXml($this->arrayXml);
        $stub->printByXml();
    }

    public function testGetBoolClass()
    {
        $stub = $this->getMockBuilder('\Dephpug\Exporter\Exporter')
              ->setMethods(['printByClass'])
              ->getMock();

        $stub->expects($this->once())
            ->method('printByClass')
            ->with(new \Dephpug\Exporter\Type\BoolExporter());

        $stub->setXml($this->boolXml);
        $stub->printByXml();
    }

    public function testGetFloatClass()
    {
        $stub = $this->getMockBuilder('\Dephpug\Exporter\Exporter')
              ->setMethods(['printByClass'])
              ->getMock();

        $stub->expects($this->once())
            ->method('printByClass')
            ->with(new \Dephpug\Exporter\Type\FloatExporter());

        $stub->setXml($this->floatXml);
        $stub->printByXml();
    }

    public function testGetIntegerClass()
    {
        $stub = $this->getMockBuilder('\Dephpug\Exporter\Exporter')
              ->setMethods(['printByClass'])
              ->getMock();

        $stub->expects($this->once())
            ->method('printByClass')
            ->with(new \Dephpug\Exporter\Type\IntegerExporter());

        $stub->setXml($this->integerXml);
        $stub->printByXml();
    }

    public function testGetNullClass()
    {
        $stub = $this->getMockBuilder('\Dephpug\Exporter\Exporter')
              ->setMethods(['printByClass'])
              ->getMock();

        $stub->expects($this->once())
            ->method('printByClass')
            ->with(new \Dephpug\Exporter\Type\NullExporter());

        $stub->setXml($this->nullXml);
        $stub->printByXml();
    }

    public function testGetObjectClass()
    {
        $stub = $this->getMockBuilder('\Dephpug\Exporter\Exporter')
              ->setMethods(['printByClass'])
              ->getMock();

        $stub->expects($this->once())
            ->method('printByClass')
            ->with(new \Dephpug\Exporter\Type\ObjectExporter());

        $stub->setXml($this->objectXml);
        $stub->printByXml();
    }

    public function testGetResourceClass()
    {
        $stub = $this->getMockBuilder('\Dephpug\Exporter\Exporter')
              ->setMethods(['printByClass'])
              ->getMock();

        $stub->expects($this->once())
            ->method('printByClass')
            ->with(new \Dephpug\Exporter\Type\ResourceExporter());

        $stub->setXml($this->resourceXml);
        $stub->printByXml();
    }

    public function testGetStringClass()
    {
        $stub = $this->getMockBuilder('\Dephpug\Exporter\Exporter')
              ->setMethods(['printByClass'])
              ->getMock();

        $stub->expects($this->once())
            ->method('printByClass')
            ->with(new \Dephpug\Exporter\Type\StringExporter());

        $stub->setXml($this->stringXml);
        $stub->printByXml();
    }

    public function testGetUnknowClass()
    {
        $stub = $this->getMockBuilder('\Dephpug\Exporter\Exporter')
              ->setMethods(['printByClass'])
              ->getMock();

        $stub->expects($this->once())
            ->method('printByClass')
            ->with(new \Dephpug\Exporter\Type\UnknownExporter());

        $stub->setXml($this->unknowXml);
        $stub->printByXml();
    }
}
