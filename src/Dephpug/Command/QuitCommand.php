<?php

namespace Dephpug\Command;

use Dephpug\Exception\ExitProgram;
use Dephpug\Output;
use Dephpug\Readline;

class QuitCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'Quit';
    }

    public function getShortDescription()
    {
        
    }

    public function getDescription()
    {
        
    }

    public function getRegexp()
    {
        return '/^q(?:uit)?/i';
    }

    public function exec()
    {
        $readline = new Readline();
        $response = $readline->scan('Are you sure? (y/n): ');
        if('y' === strtolower($response))
        {
            throw new ExitProgram('Closing dephpugger');
        }
    }

}