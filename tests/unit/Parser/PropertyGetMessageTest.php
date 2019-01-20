<?php

namespace Parser;

use Dephpug\Parser\PropertyGetMessageEvent;

class PropertyGetMessageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     */
    protected function _before()
    {
        $this->propertyGetMessage = new PropertyGetMessageEvent();
    }

    public function testPassingToExporter()
    {
        // Call match exporter->setXml
        $this->propertyGetMessage->exporter = $this->getMockBuilder('\Dephpug\Exporter\Exporter')
            ->setMethods(['setXml', 'isContentToPrint'])
            ->getMock();

        $this->propertyGetMessage->exporter->expects($this->once())
            ->method('setXml')
            ->with('<?xml ?>');

        $this->propertyGetMessage->match('<?xml ?>');
    }

    public function test()
    {
        // Call match exporter->setXml
        $this->propertyGetMessage->exporter = $this->getMockBuilder('\Dephpug\Exporter\Exporter')
            ->setMethods(['printByXml'])
            ->getMock();

        $this->propertyGetMessage->exporter->expects($this->once())
            ->method('printByXml');

        $this->propertyGetMessage->exec('<?xml ?>');
    }
}
