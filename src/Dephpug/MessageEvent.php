<?php

namespace Dephpug;

require_once __DIR__.'/Interfaces/iMessageEvent.php';
require_once __DIR__.'/Interfaces/iCore.php';

use Dephpug\Interfaces\iMessageEvent;
use Dephpug\Interfaces\iCore;

abstract class MessageEvent implements iMessageEvent, iCore
{
    public function setCore(&$core)
    {
        $this->core = $core;
    }

    public function match(string $xml)
    {
    }
}
