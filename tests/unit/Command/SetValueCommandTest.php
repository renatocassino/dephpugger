<?php

namespace Command;

use Dephpug\Command\SetValueCommand;

class SetValueCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     */
    protected function _before()
    {
        $this->setValueCommand = new SetValueCommand();
    }

    public function testInstantiateAIntegerVar()
    {
        $regexp = $this->setValueCommand->getRegexp();
        $matched = preg_match($regexp, '$var = 1');
        $this->assertTrue((bool) $matched);
    }

    public function testInstantiateACamelCaseIntegerVar()
    {
        $regexp = $this->setValueCommand->getRegexp();
        $matched = preg_match($regexp, '$varName = 1');
        $this->assertTrue((bool) $matched);
    }

    public function testInstantiateAIntegerVarWithUnderscore()
    {
        $regexp = $this->setValueCommand->getRegexp();
        $matched = preg_match($regexp, '$var_name = 1');
        $this->assertTrue((bool) $matched);
    }

    public function testInstantiateAParameterObjectIntegerVar()
    {
        $regexp = $this->setValueCommand->getRegexp();
        $matched = preg_match($regexp, '$obj->number = 1');
        $this->assertTrue((bool) $matched);
    }

    public function testInstantiateAVariableWithDoubleValue()
    {
        $regexp = $this->setValueCommand->getRegexp();
        $matched = preg_match($regexp, '$pi = 3.14');
        $this->assertTrue((bool) $matched);
    }

    public function testInstantiateAVariableWithUppercase()
    {
        $regexp = $this->setValueCommand->getRegexp();
        $matched = preg_match($regexp, '$VAR = 1');
        $this->assertTrue((bool) $matched);
    }

    public function testInstantiateAVariableWithStringType()
    {
        $regexp = $this->setValueCommand->getRegexp();
        $matched = preg_match($regexp, '$name = "My Name"');
        $this->assertTrue((bool) $matched);
    }

    public function testInstantiateAVariableWithPlic()
    {
        $regexp = $this->setValueCommand->getRegexp();
        $matched = preg_match($regexp, '$name = "My Name"');
        $this->assertTrue((bool) $matched);
    }

    public function testInstantiateWithoutSpacesBetweenEquals()
    {
        $regexp = $this->setValueCommand->getRegexp();
        $matched = preg_match($regexp, '$var=1');
        $this->assertTrue((bool) $matched);
    }

    public function testInstantiateWithMultipleSpacesBetweenEquals()
    {
        $regexp = $this->setValueCommand->getRegexp();
        $matched = preg_match($regexp, '$var  =  1');
        $this->assertTrue((bool) $matched);
    }

    public function testInstantiateWithSemicolon()
    {
        $regexp = $this->setValueCommand->getRegexp();
        $matched = preg_match($regexp, '$var = 1;');
        $this->assertTrue((bool) $matched);
    }

    public function testExecution()
    {
        $core = new \stdClass();
        $core->dbgpClient = $this->getMockBuilder('\Dephpug\DbgpClient')
            ->setMethods(['propertySet'])
            ->getMock();

        $this->setValueCommand->core = $core;

        $this->setValueCommand->core->dbgpClient->expects($this->once())
            ->method('propertySet')
            ->will($this->returnValue('varname', 123));

        $this->setValueCommand->match = ['', 'varname', '123'];
        $this->setValueCommand->exec();
    }
}
