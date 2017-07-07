<?php

namespace Dephpug\Runner;

use Dephpug\Output;

abstract class Runner
{
    public $config = null;
    public $output;

    public function __construct()
    {
        $this->output = Output::getOutput();
    }

    

    public function setConfig($config)
    {
        $this->config = $config;
    }
}
