<?php

namespace Dephpug\Command;

class ContinueCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'Continue';
    }

    public function getShortDescription()
    {
        return 'Run the script to the next breakpoint or finish the code';
    }

    public function getDescription()
    {
        
    }

    public function getAlias()
    {
        return 'c | continue';
    }

    public function getRegexp()
    {
        return '/^c(?:ont(?:inue)?)?/i';
    }

    public function exec()
    {
        $this->core->dbgpServer->sendCommand('run -i 1');
    }
}
