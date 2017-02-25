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
        $this->assertTrue($command == [true, 'step_over -i 1']);
    }

    public function testStepIntoCommand()
    {
        $command = CommandAdapter::convertCommand('s', 1);
        $this->assertTrue($command == [true, 'step_into -i 1']);
    }

    public function testInvalidCommand()
    {
        $command = CommandAdapter::convertCommand('blabla', 1);
        $this->assertTrue($command == [false, 'blabla']);
    }
}