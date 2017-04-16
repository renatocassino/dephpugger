<?php
namespace Command;

use Dephpug\Command\NextCommand;

class NextCommandTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->nextCommand = new NextCommand();
    }

    public function testRegexpAliasN()
    {
        $regexp = $this->nextCommand->getRegexp();
        $matched = preg_match($regexp, 'n');
        $this->assertTrue((bool) $matched);
    }

    public function testRegexpNext()
    {
        $regexp = $this->nextCommand->getRegexp();
        $matched = preg_match($regexp, 'next');
        $this->assertTrue((bool) $matched);
    }

    public function testExecution()
    {
        $core = new \stdClass();
        $core->dbgpServer = $this->getMockBuilder('\Dephpug\DbgpServer')
                          ->setMethods(['sendCommand'])
                          ->getMock();

        $core->dbgpServer->expects($this->once())
            ->method('sendCommand')
            ->with('step_over -i 1');

        $this->nextCommand->core = $core;
        $this->nextCommand->exec();
    }
}
