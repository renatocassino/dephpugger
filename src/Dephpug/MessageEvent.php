<?php

namespace Dephpug;

require_once __DIR__.'/Interfaces/iMessageEvent.php';
require_once __DIR__.'/Interfaces/iCore.php';

use Dephpug\Interfaces\iMessageEvent;
use Dephpug\Interfaces\iCore;

/**
 * Abstract class to all Message Event intercepting when DBGP send a XML
 * message to debug
 */
abstract class MessageEvent implements iMessageEvent, iCore
{
    /**
     * Set core as a pointer
     *
     * @param  obj $core
     * @return void
     */
    public function setCore(&$core)
    {
        $this->core = $core;
    }

    /**
     * Method match to implement and check if match with conditions
     *
     * @param  string $xml
     * @return void
     */
    public function match(string $xml)
    {
        Output::print('<fg=red>You must implement the method `match`</>');
    }
}
