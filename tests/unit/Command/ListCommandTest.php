<?php

namespace Command;

use Dephpug\Command\ListCommand;

class ListCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     */
    protected function _before()
    {
        $this->listCommand = new ListCommand();
    }

    public function testRegexpAliasL()
    {
        $regexp = $this->listCommand->getRegexp();
        $matched = preg_match($regexp, 'l');
        $this->assertTrue((bool) $matched);
    }

    public function testRegexpList()
    {
        $regexp = $this->listCommand->getRegexp();
        $matched = preg_match($regexp, 'list');
        $this->assertTrue((bool) $matched);
    }
}
