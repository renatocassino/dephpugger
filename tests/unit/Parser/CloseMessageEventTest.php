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

    public function testExceptionMessage()
    {
        $this->expectException(\Dephpug\Exception\QuitException::class);
        $this->expectExceptionMessage('Closing request');
        $this->expectExceptionCode(0);
        $this->closeMessageEvent->exec();
    }
}
