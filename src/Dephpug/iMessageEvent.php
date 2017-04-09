<?php

namespace Dephpug;

interface iMessageEvent
{
    public function match(string $xml);
}