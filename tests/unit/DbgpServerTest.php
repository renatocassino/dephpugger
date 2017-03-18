<?php

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;

class DbgpServerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $output = new ConsoleOutput();
        $output->setFormatter(new OutputFormatter(true));
    }

    protected function _after()
    {
    }

    // tests
    public function testMe()
    {
    }
}
