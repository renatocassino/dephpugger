<?php

namespace Dephpug\Command;

class ContinueCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'Continue';
    }

    public function getShortDescription()
    {
        return 'Run the script to the next breakpoint or finish the code';
    }

    public function getDescription()
    {
        return implode(
            PHP_EOL,
            [
            'This command make the debugger run again until find a next breakpoint or finish the script (request or cli script).',
            'The command is `run -i 1` in dbgp protocol.',
            ]
        );
    }

    public function getAlias()
    {
        return 'c / continue';
    }

    public function getRegexp()
    {
        return '/^c(?:ont(?:inue)?)?$/i';
    }

    public function exec()
    {
        $this->core->dbgpClient->continue();
    }
}
