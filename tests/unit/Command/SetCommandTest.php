<?php
namespace Command;

use Dephpug\Command\SetCommand;

class SetCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     */
    protected function _before()
    {
        $this->setCommand = new SetCommand();
    }

    public function testRegexpSettingVerboseModeAsTrue()
    {
        $regexp = $this->setCommand->getRegexp();
        $matched = preg_match($regexp, 'set verboseMode:1');
        $this->assertTrue((bool) $matched);
    }

    public function testRegexpWithSpaces()
    {
        $regexp = $this->setCommand->getRegexp();
        $matched = preg_match($regexp, 'set VerboseMode : 1');
        $this->assertTrue((bool) $matched);
    }

    public function testRegexpWithLineOffsetAndNumber()
    {
        $regexp = $this->setCommand->getRegexp();
        $matched = preg_match($regexp, 'set lineOffset : 1');
        $this->assertTrue((bool) $matched);
    }
}
