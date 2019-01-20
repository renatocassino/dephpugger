<?php

namespace Dephpug\Interfaces;

/**
 * Set methods for all commands
 */
interface iCommand
{
    /**
     * Get the Command name
     */
    public function getName();

    /**
     * Get the alias of the command
     */
    public function getAlias();

    /**
     * Get a one line description of this command
     */
    public function getShortDescription();

    /**
     * Get the full description of the command
     */
    public function getDescription();

    /**
     * The regexp to match the command
     */
    public function getRegexp();
}
