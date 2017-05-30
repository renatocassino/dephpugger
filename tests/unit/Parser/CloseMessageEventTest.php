<?php

namespace Parser;

use Dephpug\Parser\CloseMessageEvent;

class CloseMessageEventTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     */
    public function _before()
    {
        $this->closeMessageEvent = new CloseMessageEvent();
    }

    public function testErrorMatchingRunState()
    {
        $assert = $this->closeMessageEvent->match('status="run"');
        $this->assertTrue(!$assert);
    }

    public function testMatchingStatusStoping()
    {
        $assert = $this->closeMessageEvent->match('status="stopping"');
        $this->assertTrue($assert);
    }

    public function testMatchingStatusStoped()
    {
        $assert = $this->closeMessageEvent->match('status="stopped"');
        $this->assertTrue($assert);
    }

    /**
     * @expectedException     Dephpug\Exception\QuitException
     * @expectedExceptionMessage Closing request
     * @expectedExceptionCode 0
     */
    public function testExceptionMessage()
    {
        $this->closeMessageEvent->exec();
    }
}
