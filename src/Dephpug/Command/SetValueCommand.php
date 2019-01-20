<?php

namespace Dephpug\Command;

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
        return implode(
            ' ',
            [
            'You can set the value and type.',
            'Ex: $var = 1',
            ]
        );
    }

    public function getAlias()
    {
        return '$var=1';
    }

    public function getRegexp()
    {
        return '/^\$([\w_\[\]\"\\\'\-\>\{\}]+) *= *["\']?([\'\"\w\. ]+)["\']?\;?$/';
    }

    public function exec()
    {
        $varname = $this->match[1];
        $value = $this->match[2];
        $this->core->dbgpClient->propertySet($varname, $value);
    }
}
