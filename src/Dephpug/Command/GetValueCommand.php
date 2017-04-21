<?php

namespace Dephpug\Command;

class GetValueCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'GetValue';
    }

    public function getShortDescription()
    {
        return 'Get variable value';
    }

    public function getDescription()
    {
        return implode(' ', [
            'You can get the value and type. The value\'s format is in method var_export.',
            'See more in http://php.net/manual/en/function.var-export.php',
        ]);
    }

    public function getAlias()
    {
        return '$var';
    }

    public function getRegexp()
    {
        return '/^\$([\w_\[\]\"\\\'\{\}0-9]+);?$/';
    }

    public function exec()
    {
        $this->core->dbgpClient->propertyGet($this->match[1]);
    }
}
