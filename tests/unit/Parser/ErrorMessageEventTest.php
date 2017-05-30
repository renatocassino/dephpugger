<?php

namespace Parser;

use Dephpug\Parser\ErrorMessageEvent;

class ErrorMessageEventTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     */
    protected function _before()
    {
        $this->errorMessageEvent = new ErrorMessageEvent();
    }

    public function testMatchingErrorMessage()
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
        $assert = $this->errorMessageEvent->match($xml);
        $this->assertTrue($assert);
        $this->assertEquals(300, $this->errorMessageEvent->code);
        $this->assertEquals("\n   can not get property\n  ", $this->errorMessageEvent->message);
    }

    public function testUnmatchingErrorMessageWithASuccessXml()
    {
        $xml = <<<'EOL'
<?xml version="1.0" encoding="iso-8859-1"?>
<response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="property_get" transaction_id="1">
 <property name="$d" fullname="$d" type="float">
  <![CDATA[3.141]]>
 </property>
</response>
EOL;
        $assert = $this->errorMessageEvent->match($xml);
        $this->assertTrue(!$assert);
    }
}
