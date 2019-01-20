<?php

namespace Parser;

use Dephpug\Parser\FilePrinterMessageEvent;
use Dephpug\FilePrinter;

class FilePrinterMessageEventTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     */
    protected function _before()
    {
        $this->filePrinterMessageEvent = new FilePrinterMessageEvent();
    }

    public function testMatchFileAndLineNumber()
    {
        $xml = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="step_over" transaction_id="1" status="break" reason="ok">
 <xdebug:message filename="file:///path/of/project/index.php" lineno="34">
 </xdebug:message>
</response>
EOL;

        $matched = $this->filePrinterMessageEvent->match($xml);
        $lineNumber = $this->filePrinterMessageEvent->fileNumber;
        $filename = $this->filePrinterMessageEvent->fileName;

        $this->assertTrue($matched);
        $this->assertEquals('/path/of/project/index.php', $filename);
        $this->assertEquals(34, $lineNumber);
    }

    public function testUnmatchingFilePrinter()
    {
        $xml = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="property_get" transaction_id="1" status="break" reason="ok">
 <error code="300">
  <message>
   <![CDATA[can not get property]]>
  </message>
 </error>
</response>
EOL;

        $matched = $this->filePrinterMessageEvent->match($xml);
        $this->assertTrue(!$matched);
    }

    public function testExecution()
    {
        $core = new \stdClass();
        $core->filePrinter = $this->getMockBuilder('\Dephpug\FilePrinter')
            ->setMethods(['setFilename', 'showFile'])
            ->getMock();
        $core->config = new \stdClass();
        $core->config->debugger = ['lineOffset' => 1];

        $this->filePrinterMessageEvent->core = $core;
        $this->filePrinterMessageEvent->fileName = '/path/of/project/index.php';
        $this->filePrinterMessageEvent->fileNumber = 30;

        $core->filePrinter->expects($this->once())
            ->method('showFile');

        $core->filePrinter->expects($this->once())
            ->method('setFilename')
            ->with('/path/of/project/index.php');

        $this->filePrinterMessageEvent->exec();
    }
}
