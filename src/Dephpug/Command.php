<?php

namespace Dephpug;

require_once __DIR__.'/Interfaces/iCommand.php';
require_once __DIR__.'/Interfaces/iCore.php';

use Dephpug\Interfaces\iCommand;
use Dephpug\Interfaces\iCore;

/**
 * Abstract class that inherit for all commands in Dephpugger
 *
 * Every command is a class that have Dephpug\Command as a inherit
 * that receive the $core and must create methods getRegexp, description
 * and anothers in interface iCommand and iCore.
 */
abstract class Command implements iCommand, iCore
{
    /**
     * The order to run commands asc. Level 1 run first than Level 2
     */
    public $level = 1;

    /**
     * The core instance with all attributes
     */
    public $core;

    /**
     * Regex to match command
     */
    public $match;

    /**
     * Method to set core as a pointer to control the same instance
     *
     * @param  obj $core
     * @return void
     */
    public function setCore(&$core)
    {
        $this->core = $core;
    }

    /**
     * Method to check if match a command with this object and instantiate
     * the attribute $this->match with regexp values
     *
     * @param  string $command
     * @return void
     */
    public function match($command)
    {
        return preg_match($this->getRegexp(), $command, $this->match);
    }

    /**
     * Get the big description using method getName and getAlias to describe
     * the full info of this one
     *
     * @return string
     */
    public function getBigDescription()
    {
        $content = "\n\n<options=bold>Method name: {$this->getName()}</>\n\n";
        $content .= "You can call as: <options=bold>{$this->getAlias()}</>\n";
        $content .= "Regex to match this command: <options=bold>{$this->getRegexp()}</>\n\n";
        $content .= "<comment>{$this->getShortDescription()}";
        $content .= "\n\n";

        return $content.$this->getDescription()."</comment>\n";
    }

    /**
     * Get the name of the command
     *
     * @return string Indicates the name of the command
     */
    public function getName()
    {
        return 'Not implemented plugin';
    }
}
