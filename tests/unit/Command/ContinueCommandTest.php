<?php
namespace Command;

use Dephpug\Command\ContinueCommand;

class ContinueCommandTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->continueCommand = new ContinueCommand();
    }

    public function testRegexpC()
    {
        $regexp = $this->continueCommand->getRegexp();
        $this->assertTrue((bool) preg_match($regexp, 'c'));
    }

    public function testRegexpCont()
    {
        $regexp = $this->continueCommand->getRegexp();
        $this->assertTrue((bool) preg_match($regexp, 'cont'));
    }

    public function testRegexpContinue()
    {
        $regexp = $this->continueCommand->getRegexp();
        $this->assertTrue((bool) preg_match($regexp, 'continue'));
    }
}