<?php

namespace Command;

use Dephpug\Command\GetValueCommand;

class GetValueCommandTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->getValueCommand = new GetValueCommand();
    }

    public function testGetSimpleVar()
    {
        $regexp = $this->getValueCommand->getRegexp();
        $matched = preg_match($regexp, '$variable');
        $this->assertTrue((bool) $matched);
    }

    public function testGetSimpleVarWithUppercase()
    {
        $regexp = $this->getValueCommand->getRegexp();
        $matched = preg_match($regexp, '$variableName');
        $this->assertTrue((bool) $matched);
    }

    public function testGetVarWithUnderscore()
    {
        $regexp = $this->getValueCommand->getRegexp();
        $matched = preg_match($regexp, '$variable_name');
        $this->assertTrue((bool) $matched);
    }

    public function testGetVarWithHyphenUnmatch()
    {
        $regexp = $this->getValueCommand->getRegexp();
        $matched = preg_match($regexp, '$variable-name');
        $this->assertTrue(!$matched);
    }

    public function testGetVarWithNumber()
    {
        $regexp = $this->getValueCommand->getRegexp();
        $matched = preg_match($regexp, '$v10');
        $this->assertTrue((bool) $matched);
    }

    public function testGetVarWithBraces()
    {
        $regexp = $this->getValueCommand->getRegexp();
        $matched = preg_match($regexp, '${\'variable_name\'}');
        $this->assertTrue((bool) $matched);
    }

    public function testGetVarWithBrackets()
    {
        $regexp = $this->getValueCommand->getRegexp();
        $matched = preg_match($regexp, '$variable_name[0]');
        $this->assertTrue((bool) $matched);
    }

    public function testCommandSent()
    {
        $core = new \stdClass();
        $core->dbgpClient = $this->getMockBuilder('\Dephpug\DbgpClient')
                          ->setMethods(['propertyGet'])
                          ->getMock();

        $core->dbgpClient->expects($this->once())
            ->method('propertyGet')
            ->with('variableName');

        $this->getValueCommand->match = ['', 'variableName'];

        $this->getValueCommand->core = $core;
        $this->getValueCommand->exec();
    }
}
