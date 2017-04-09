<?php

namespace Dephpug;

require_once(__DIR__ . '/iCommand.php');

abstract class Command implements iCommand
{
    public $dbgpServer;
    public $core;

    public function setCore(&$core)
    {
        $this->core = $core;
    }

    public function getName()
    {
        return 'Not implemented plugin';
    }
}