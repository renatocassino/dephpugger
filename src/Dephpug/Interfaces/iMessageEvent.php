<?php

namespace Dephpug\Interfaces;

interface iMessageEvent
{
    public function match(string $xml);
    public function exec();
}