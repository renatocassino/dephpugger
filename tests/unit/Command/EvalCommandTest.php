<?php
namespace Command;

use Dephpug\Command\EvalCommand;

class EvalCommandTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->evalCommand = new EvalCommand();
    }

    public function testLevelMoreThanOne()
    {
        $this->assertTrue($this->evalCommand->level > 1);
    }

    public function testAlwaysCommandMatch()
    {
        $regexp = $this->evalCommand->getRegexp();
        $matched = preg_match($regexp, 'abcdefghijklmnopqrstuvwxyz0123456789-_./!@#$%*()[]{}');
        $this->assertTrue((bool) $matched);
    }

    public function testCommandSent()
    {
        $core = new \stdClass();
        $core->dbgpServer = $this->getMockBuilder('\Dephpug\DbgpServer')
                          ->setMethods(['sendCommand'])
                          ->getMock();

        $core->dbgpServer->expects($this->once())
            ->method('sendCommand')
            ->with('eval -i 1 -- '.base64_encode('property_get -i 1'));

        $this->evalCommand->match = ['', 'property_get -i 1'];

        $this->evalCommand->core = $core;
        $this->evalCommand->exec();
    }
}