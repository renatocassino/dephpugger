<?php

namespace Dephpug\Command;

class DbgpCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'Dbgp';
    }

    public function getShortDescription()
    {
        return 'Run dbgp command';
    }

    public function getDescription()
    {
        return implode(
            ' ',
            [
            ' Command to run native dbgp commands.',
            'The dephpugger convert commands to dbgp protocol format to make a developer\'s life easier (like `n` to `step_over -i 1`).',
            "You can send commands in dbgp format using the command `dbgp(<commandName>)`.\n\n",
            "Example: dbgp(property_get -i 3 variableName)\n",
            "(string) => My Value\n\n",
            'You can see how to write dbgp commands, you can see in https://xdebug.org/docs-dbgp.php',
            ]
        );
    }

    public function getAlias()
    {
        return 'dbgp(<command>)$';
    }

    public function getRegexp()
    {
        return '/^dbgp\(([^\)]+)\)/i';
    }

    public function exec()
    {
        if (count($this->match) > 1) {
            $this->core->dbgpClient->run($this->match[1]);
        }
    }
}
