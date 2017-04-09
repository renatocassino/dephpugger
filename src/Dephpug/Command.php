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

    public function getBigDescription()
    {
        $content = "\n\n<options=bold>Method name: {$this->getName()}</>\n\n";
        $content .= "You can call as: <options=bold>{$this->getAlias()}</>\n";
        $content .= "Regex to match this command: <options=bold>{$this->getRegexp()}</>\n\n";
        $content .= "<comment>{$this->getShortDescription()}";
        $content .= "\n\n";
        return $content . $this->getDescription() . "</comment>\n";
    }

    public function getName()
    {
        return 'Not implemented plugin';
    }
}