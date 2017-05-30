<?php

use Dephpug\Output;
use Symfony\Component\Console\Output\ConsoleOutput;

class OutputTest extends \PHPUnit\Framework\TestCase
{
    // tests
    public function testIfGettingASymfonyOutput()
    {
        $output = Output::getOutput();
        $hasASymfonyClass = ($output instanceof ConsoleOutput);
        $this->assertTrue($hasASymfonyClass);
    }
}
