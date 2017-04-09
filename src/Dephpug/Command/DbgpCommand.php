<?php

namespace Dephpug\Command;

class DbgpCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'Continue';
    }

    public function getShortDescription()
    {
        return 'Run dbgp command';
    }

    public function getDescription()
    {
        
    }

    public function getAlias()
    {
        return 'dbgp(<command>)';
    }

    public function getRegexp()
    {
        return '/^dbgp\(([^\)]+)\)/i';
    }

    public function exec()
    {
        if(count($this->match) > 1) {
            $this->core->dbgpServer->sendCommand($this->match[1]);
        }
    }
}
