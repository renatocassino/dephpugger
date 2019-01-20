<?php

namespace Dephpug\Console\concerns;

class PhpInfo
{
    public $info;

    public function getInfo()
    {
        if (null === $this->info) {
            ob_start();
            phpinfo();
            $this->info = ob_get_clean();
        }

        return $this->info;
    }

    public function getVar($variable)
    {
        $info = $this->getInfo();
        $variable = str_replace('.', '\.', $variable);
        $pattern = "/{$variable} \=\> (.+)\n/";

        if (preg_match($pattern, $info, $variableInfo)) {
            return $variableInfo[1];
        }

        return null;
    }

    public function printVar($variable, $label)
    {
        return "{$label}: <fg=red>{$this->getVar($variable)}</>";
    }

    public function getVars($pattern)
    {
        $info = $this->getInfo();
        if (preg_match_all($pattern, $info, $variablesInfo)) {
            return $variablesInfo;
        }

        return null;
    }

    public function checkPHPVersion()
    {
        return (int) phpversion()[0] >= 7;
    }

    public function xdebugInstalled()
    {
        return extension_loaded('xdebug');
    }

    public function socketsInstalled()
    {
        return extension_loaded('sockets');
    }

    public function xdebugIsActive()
    {
        if ($this->xdebugInstalled()) {
            return xdebug_is_enabled();
        }

        return false;
    }
}
