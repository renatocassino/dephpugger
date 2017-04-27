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
        // Worst way to solve this problem
        $this->core->dbgpClient->propertyGet($this->match[1]);
        $response = $this->core->dbgpClient->getResponse();

        if(!preg_match('/error code/', $response)) {
            $this->core->dbgpClient->propertyGet($this->match[1]);
            return;
        }

        $key = uniqid();
        $this->core->dbgpClient->eval('$GLOBALS["'.$key.'"]=$'.$this->match[1]);
        $this->core->dbgpClient->getResponse();
        $command = '$GLOBALS["'.$key.'__"]=var_export($GLOBALS["'.$key.'"], true)';

        $this->core->dbgpClient->eval($command);
    }
}

/*
 * Next hypoteses
 * context_names => Return xml with context_get types
 * context_get -d {num} for each return above
 * Search if this variable exists in the above list, if exist return, if not, send error message
 */