<?php

namespace Dephpug\Command;

use Dephpug\Exception\ExitProgram;
use Dephpug\Output;

class SetValueCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'SetValue';
    }

    public function getShortDescription()
    {
        return 'Set variable value';
    }

    public function getDescription()
    {
        return join(' ', [
            'You can set the value and type.',
            'Ex: $var = 1',
        ]);
    }

    public function getAlias()
    {
        return '$var=1';
    }

    public function getRegexp()
    {
        return '/^\$([\w_\[\]\"\\\'\-\>\{\}]+) *= *([\'\"\w\.]+)\;?$/';
    }

    public function exec()
    {
        $varname = $this->match[1];
        $value = base64_encode($this->match[2]);
        $command = "property_set -i 1 -n \${$varname} -- {$value}";
        $this->core->dbgpServer->sendCommand($command);
    }
}
