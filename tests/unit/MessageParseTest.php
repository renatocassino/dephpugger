<?php

use Dephpug\MessageParse;

class MessageParseTest extends \PHPUnit\Framework\TestCase
{
    protected $messageParse;

    /**
     * @before
     */
    protected function _before()
    {
        $this->messageParse = new MessageParse();
    }

    public function testRemovingNumbersBeforeXML()
    {
        $message = '400<?xml ...';
        $formatedMessage = $this->messageParse->formatMessage($message);
        $this->assertEquals('<?xml ...', $formatedMessage);
    }
}
