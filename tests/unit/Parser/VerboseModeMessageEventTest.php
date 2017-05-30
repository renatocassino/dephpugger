<?php

namespace Parser;

use Dephpug\Parser\VerboseModeMessageParse;

class VerboseModeMessageEventTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     */
    protected function _before()
    {
        $this->verboseMode = new VerboseModeMessageParse();
    }

    public function testMatch()
    {
        $core = new \stdClass();
        $core->config = new \stdClass();
        $core->config->debugger = ['verboseMode' => true];
        $this->verboseMode->core = $core;
        $this->assertTrue($this->verboseMode->match(''));
    }

    public function testUnmatch()
    {
        $core = new \stdClass();
        $core->config = new \stdClass();
        $core->config->debugger = ['verboseMode' => false];
        $this->verboseMode->core = $core;
        $this->assertTrue(!$this->verboseMode->match(''));
    }
}
