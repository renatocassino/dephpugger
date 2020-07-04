<?php

namespace Command;

use Dephpug\Command\HelpCommand;
use PHPUnit\Framework\TestCase;

class HelpCommandTest extends TestCase
{
    /**
     * @before
     */
    protected function _before()
    {
        $this->helpCommand = new HelpCommand();
    }

    public function testRegexpH()
    {
        $regexp = $this->helpCommand->getRegexp();
        $this->assertTrue((bool) preg_match($regexp, 'h'));
    }

    public function testRegexpHelp()
    {
        $regexp = $this->helpCommand->getRegexp();
        $this->assertTrue((bool) preg_match($regexp, 'help'));
    }

    public function testRegexpHelpSomeCommand()
    {
        $regexp = $this->helpCommand->getRegexp();
        $this->assertTrue((bool) preg_match($regexp, 'help someCommand'));
    }
}
