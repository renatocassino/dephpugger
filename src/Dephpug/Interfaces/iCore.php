<?php

namespace Dephpug\Interfaces;

/**
 * Interface to get the core as a pointer
 */
interface iCore
{
    /**
     * Set the current core as the same instance
     */
    public function setCore(&$core);
}
