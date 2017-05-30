<?php

namespace Parser;

use Dephpug\Parser\InitMessageEvent;

class InitMessageEventTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     */
    protected function _before()
    {
        $this->initMessageEvent = new InitMessageEvent();
    }

    // tests
    public function testMatching()
    {
        $matched = $this->initMessageEvent->match('<?xml>.....<init ...');
        $this->assertTrue((bool) $matched);
    }

    public function testUnmatching()
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

        $matched = $this->initMessageEvent->match($xml);
        $this->assertTrue(!$matched);
    }
}
