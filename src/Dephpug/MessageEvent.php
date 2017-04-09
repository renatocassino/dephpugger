<?php

namespace Dephpug;

require_once __DIR__ . '/iMessageEvent.php';

abstract class MessageEvent implements iMessageEvent
{
    public function setCore($core)
    {
        $this->core = $core;
    }

    public function match(string $xml)
    {
        
    }
}