<?php

use Dephpug\Output;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;

class OutputTest extends \Codeception\Test\Unit
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
    public function testIfGettingASymfonyOutput()
    {
        $output = Output::getOutput();
        $hasASymfonyClass = ($output instanceof ConsoleOutput);
        $this->assertTrue($hasASymfonyClass);
    }
}