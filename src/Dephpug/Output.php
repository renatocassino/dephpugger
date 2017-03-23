<?php

namespace Dephpug;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;

class Output
{
    private static $output;
    public static function getOutput()
    {
        if(!self::$output) {
            $output = new ConsoleOutput();
            $output->setFormatter(new OutputFormatter(true));
            self::$output = $output;
        }
        return self::$output;
    }

    public static function print($message)
    {
        $output = self::getOutput();
        $output->writeln($message);
    }
}