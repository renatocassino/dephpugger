<?php
namespace Command;

use Dephpug\Command\ListPreviousCommand;

class ListPreviousCommandTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
        $this->listPreviousCommand = new ListPreviousCommand();
    }

    public function testRegexpAliasLP()
    {
        $regexp = $this->listPreviousCommand->getRegexp();
        $matched = preg_match($regexp, 'lp');
        $this->assertTrue((bool) $matched);
    }

    public function testRegexpListPrevious()
    {
        $regexp = $this->listPreviousCommand->getRegexp();
        $matched = preg_match($regexp, 'list_previous');
        $this->assertTrue((bool) $matched);
    }
}