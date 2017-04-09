<?php

namespace Dephpug;

require_once(__DIR__ . '/Interfaces/iCommand.php');
require_once(__DIR__ . '/Interfaces/iCore.php');

use Dephpug\Interfaces\iCommand;
use Dephpug\Interfaces\iCore;

abstract class Command implements iCommand, iCore
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