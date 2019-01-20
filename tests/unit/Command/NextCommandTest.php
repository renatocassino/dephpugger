<?php

namespace Command;

use Dephpug\Command\NextCommand;

class NextCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     */
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
        $core->dbgpClient = $this->getMockBuilder('\Dephpug\DbgpClient')
            ->setMethods(['next'])
            ->getMock();

        $core->dbgpClient->expects($this->once())
            ->method('next');

        $this->nextCommand->core = $core;
        $this->nextCommand->exec();
    }
}
