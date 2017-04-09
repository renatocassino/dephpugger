<?php

namespace Dephpug\Command;

class ListCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'List';
    }

    public function getShortDescription()
    {
        return 'List next file lines';
    }

    public function getDescription()
    {
        return join(' ', [
            'This command will get the current file and show the next lines.',
            "For example, if you have the :\n\n",
            "=> 2. echo 'This ';\n    3. echo 'is ';\n\n",
            "Will show the next lines in file.\n\n",
            "   4. echo 'my ';\n    5. echo 'code!';"
        ]);
    }

    public function getAlias()
    {
        return 'l | list';
    }

    public function getRegexp()
    {
        return '/^l(?:ist)?/i';
    }

    public function exec()
    {
        //        $this->core->dbgpServer->sendCommand('step_over -i 1');
    }
}
