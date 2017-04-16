<?php

use Dephpug\MessageParse;

class MessageParseTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $messageParse;

    protected function _before()
    {
        $this->messageParse = new MessageParse();
    }

    protected function _after()
    {
    }

    // tests
    public function testRemovingNumbersBeforeXML()
    {
        $message = '400<?xml ...';
        $formatedMessage = $this->messageParse->formatMessage($message);
        $this->assertEquals('<?xml ...', $formatedMessage);
    }
}
