<?php

namespace Dephpug\Command;

class NextCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'Next';
    }

    public function getShortDescription()
    {
        return 'Step over in breakpoint';
    }

    public function getDescription()
    {
        
    }

    public function getAlias()
    {
        return 'n | next';
    }

    public function getRegexp()
    {
        return '/^n(?:ext)?/i';
    }

    public function exec()
    {
        $this->core->dbgpServer->sendCommand('step_over -i 1');
    }
}
