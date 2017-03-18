<?php

use Dephpug\CommandAdapter;

class CommandAdapterTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testNextCommand()
    {
        $command = CommandAdapter::convertCommand('n', 1);
        $this->assertEquals('step_over -i 1', $command);
    }

    public function testStepIntoCommand()
    {
        $command = CommandAdapter::convertCommand('s', 1);
        $this->assertEquals('step_into -i 1', $command);
    }

    public function testContinueRunCommand()
    {
        $command = CommandAdapter::convertCommand('c', 1);
        $this->assertEquals('run -i 1', $command);
    }

    public function testQuitAbbrRunCommand()
    {
        $this->expectException(\Dephpug\Exception\ExitProgram::class);
        $command = CommandAdapter::convertCommand('q', 1);
    }

    public function testQuitRunCommand()
    {
        $this->expectException(\Dephpug\Exception\ExitProgram::class);
        $command = CommandAdapter::convertCommand('quit', 1);
    }

    public function testInvalidCommand()
    {
        $command = CommandAdapter::convertCommand('blabla', 1);
        $this->assertEquals('eval -i 1 -- YmxhYmxh', $command);
    }

    public function testEvalCommand()
    {
        $command = CommandAdapter::convertCommand('dbgp(property_get -i 1 -n variable);', 1);
        $this->assertEquals('property_get -i 1 -n variable', $command);
    }

    public function testVariableGet()
    {
        $command = CommandAdapter::convertCommand('$variable', 1);
        $this->assertEquals('property_get -i 1 -n variable', $command);
    }

    public function testCallingAFunction()
    {
        $command = CommandAdapter::convertCommand('my_function()', 1);
        $this->assertEquals('eval -i 1 -- bXlfZnVuY3Rpb24oKQ==', $command);
    }

    public function testCallingAFunctionWithSemicolon()
    {
        $command = CommandAdapter::convertCommand('my_function();', 1);
        $this->assertEquals('eval -i 1 -- bXlfZnVuY3Rpb24oKTs=', $command);
    }

    public function testVariableGetWithSemicolon()
    {
        $command = CommandAdapter::convertCommand('$variable;', 1);
        $this->assertEquals('property_get -i 1 -n variable', $command);
    }

    public function testVariableSetWithoutSpacesAndSemicolon()
    {
        $command = CommandAdapter::convertCommand('$variable=33', 1);
        $this->assertEquals('property_set -i 1 -n $variable -- MzM=', $command);
    }

    public function testVariableSetWithoutSpacesWithSemicolon()
    {
        $command = CommandAdapter::convertCommand('$variable=44;', 1);
        $this->assertEquals('property_set -i 1 -n $variable -- NDQ=', $command);
    }

    public function testVariableSetWithFirstSpace()
    {
        $command = CommandAdapter::convertCommand('$variable =32;', 1);
        $this->assertEquals('property_set -i 1 -n $variable -- MzI=', $command);
    }

    public function testVariableSetWithSpaces()
    {
        $command = CommandAdapter::convertCommand('$variable = 32;', 1);
        $this->assertEquals('property_set -i 1 -n $variable -- MzI=', $command);
    }

    public function testVariableSetStringWithSimpleQuotationMarks()
    {
        $command = CommandAdapter::convertCommand('$variable = \'value\';', 1);
        $this->assertEquals('property_set -i 1 -n $variable -- J3ZhbHVlJw==', $command);
    }

    public function testVariableSetStringWithQuotationMarks()
    {
        $command = CommandAdapter::convertCommand('$variable = "value";', 1);
        $this->assertEquals('property_set -i 1 -n $variable -- InZhbHVlIg==', $command);
    }

    public function testVariableSetFloatOrDoubleValue()
    {
        $command = CommandAdapter::convertCommand('$variable = 33.12;', 1);
        $this->assertEquals('property_set -i 1 -n $variable -- MzMuMTI=', $command);
    }

    public function testIfStartsWithAString()
    {
        $command = new CommandAdapter();
        $this->assertTrue($command->startsWith('FirstWorld in my string', 'FirstWorld'));
    }

    public function testIfStartsWithAStringWithOneChar()
    {
        $command = new CommandAdapter();
        $this->assertTrue($command->startsWith('FirstWorld in my string', 'F'));
    }

    public function testIfStartsWithAStringPassingEmpty()
    {
        $command = new CommandAdapter();
        $this->assertTrue($command->startsWith('FirstWorld in my string', ''));
    }

    public function testIfDoesNotStartWithAString()
    {
        $command = new CommandAdapter();
        $this->assertFalse($command->startsWith('FirstWorld in my string', 'Second'));
    }
}
