<?php

namespace Command;

use Dephpug\Command\DbgpCommand;

class DbgpCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     */
    protected function _before()
    {
        $this->dbgpCommand = new DbgpCommand();
    }

    public function testMatchCommand()
    {
        $regexp = $this->dbgpCommand->getRegexp();
        $matched = preg_match($regexp, 'dbgp(command)');
        $this->assertTrue((bool) $matched);
    }

    public function testMatchCommandEmpty()
    {
        $regexp = $this->dbgpCommand->getRegexp();
        $matched = preg_match($regexp, 'dbgp()');
        $this->assertTrue(!$matched);
    }

    public function testMatchCommandAndCheckValue()
    {
        $regexp = $this->dbgpCommand->getRegexp();
        $matched = preg_match($regexp, 'dbgp(property_get -i 1)', $match);
        $this->assertEquals('property_get -i 1', $match[1]);
    }
}
