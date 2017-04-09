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
    public $match;

    public function setCore(&$core)
    {
        $this->core = $core;
    }

    public function match($command)
    {
        return preg_match($this->getRegexp(), $command, $this->match);
    }

    public function getName()
    {
        return 'Not implemented plugin';
    }
}