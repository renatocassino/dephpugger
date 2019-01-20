<?php

namespace Dephpug\Interfaces;

/**
 * Interface to all message parses
 */
interface iMessageEvent
{
    /**
     * Method with the rule to match the xml
     */
    public function match(string $xml);

    /**
     * Method of execution
     */
    public function exec();
}
