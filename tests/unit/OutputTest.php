<?php

use Dephpug\Output;
use Symfony\Component\Console\Output\ConsoleOutput;

class OutputTest extends \Codeception\Test\Unit
{
    // tests
    public function testIfGettingASymfonyOutput()
    {
        $output = Output::getOutput();
        $hasASymfonyClass = ($output instanceof ConsoleOutput);
        $this->assertTrue($hasASymfonyClass);
    }
}
