<?php

namespace concerns;

class PhpInfo
{
    public $info;

    public function getInfo() {
        if(null === $this->info) {
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

        if(preg_match($pattern, $info, $variableInfo)) {
            return $variableInfo[1];
        }
        return null;
    }

    public function printVar($variable, $label)
    {
        return "{$label}: <fg=red>{$this->getVar($variable)}</>";
    }
}