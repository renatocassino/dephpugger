<?php

namespace Dephpug\Command;

class ListPreviousCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'ListPrevious';
    }

    public function getShortDescription()
    {
        return 'List previous file lines';
    }

    public function getDescription()
    {
        return join(' ', [
            'This command will get the current file and show the previous lines.',
            "For example, if you have the :\n\n",
            "=> 4. echo 'my ';\n    5. echo 'code!';\n\n",
            "Will show the previous lines in file.\n\n",
            "   2. echo 'This ';\n    3. echo 'is ';",
        ]);
    }

    public function getAlias()
    {
        return 'lp / list_previous';
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
