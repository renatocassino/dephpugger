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

    /**
     * Getting the value of variable in command.
     * The DBGP protocol has a method called property_get, but this method get only
     * local variables in the current state (method, function or anything). To get
     * a global variable, the best way is is use the function var_export.
     * All methods called for a global variable return null in DBGP. To can use,
     * the code need return the value to a global variable.
     */
    public function exec()
    {
        $varname = '$'.$this->match[1];
        $key = uniqid();
        $this->core->dbgpClient->eval('$GLOBALS["'.$key.'"]='.$varname);
        $this->core->dbgpClient->getResponse();
        $command = '$GLOBALS["'.$key.'__"]=var_export($GLOBALS["'.$key.'"], true)';
        $this->core->dbgpClient->eval($command);
    }
}
