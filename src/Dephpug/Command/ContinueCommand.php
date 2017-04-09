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
        
    }

    public function getDescription()
    {
        
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
