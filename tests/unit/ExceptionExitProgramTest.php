<?php

use Dephpug\Exception\ExitProgram;

class ExceptionExitProgramTest extends \PHPUnit\Framework\TestCase
{
    public function testExceptionMessage()
    {
        try {
            throw new ExitProgram('Status 0', 0);
        } catch (ExitProgram $e) {
            $this->assertEquals('Dephpug\Exception\ExitProgram: [0]: Unexpected error - Status 0', (string) $e);
        }
    }

    public function testExceptionStatusMessage()
    {
        try {
            throw new ExitProgram('Status 0', 0);
        } catch (ExitProgram $e) {
            $this->assertEquals('Unexpected error', $e->getStatusMessage());
        }
    }

    public function testExceptionStatusMessageWithoutStatus()
    {
        try {
            throw new ExitProgram('Status 0', 999999999999999999);
        } catch (ExitProgram $e) {
            $this->assertEquals('', $e->getStatusMessage());
        }
    }

    public function testExceptionGetMessage()
    {
        try {
            throw new ExitProgram('Custom message', 0);
        } catch (ExitProgram $e) {
            $this->assertEquals('Custom message', $e->getMessage());
        }
    }
}
