<?php

namespace Dephpug;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;

/**
 * Class to print using Symfony Console Color
 *
 * @example Output::print("<fg=red>Text to print in red</>")
 */
class Output
{
    /**
     * Attribute with Symfony Output Console color
     */
    private static $output;

    /**
     * Get the output (memoize)
     */
    public static function getOutput()
    {
        if (!self::$output) {
            $output = new ConsoleOutput();
            $output->setFormatter(new OutputFormatter(true));
            self::$output = $output;
        }

        return self::$output;
    }

    /**
     * Print a content with symfony colors
     * Didn't return a string, print when this method is called
     *
     * @param  string $message Indicates message with symfony color format
     * @return void
     */
    public static function print($message)
    {
        $output = self::getOutput();
        $output->writeln($message);
    }
}
