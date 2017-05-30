<?php

namespace Command;

use Dephpug\Command\StepIntoCommand;

class StepIntoCommandTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @before
     */
    protected function _before()
    {
        $this->stepIntoCommand = new StepIntoCommand();
    }

    public function testRegexpAliasS()
    {
        $regexp = $this->stepIntoCommand->getRegexp();
        $matched = preg_match($regexp, 's');
        $this->assertTrue((bool) $matched);
    }

    public function testRegexpAliasStepInto()
    {
        $regexp = $this->stepIntoCommand->getRegexp();
        $matched = preg_match($regexp, 'step_into');
        $this->assertTrue((bool) $matched);
    }
}
