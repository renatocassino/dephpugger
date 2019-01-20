<?php

namespace Dephpug\Command;

class NextCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'Next';
    }

    public function getShortDescription()
    {
        return 'Step over in breakpoint';
    }

    public function getDescription()
    {
        return implode(
            ' ',
            [
            'This command will get the next line over the method and function calls.',
            "For example, if you have this code:\n\n",
            "=> 2. functionCall();\n    3. \$var = 1;\n\n",
            "The next command dont get inside the method `functionCall` and go to next line\n\n",
            "   2. functionCall();\n => 3. \$var = 1;",
            ]
        );
    }

    public function getAlias()
    {
        return 'n / next';
    }

    public function getRegexp()
    {
        return '/^n(?:ext)?$/i';
    }

    public function exec()
    {
        $this->core->dbgpClient->next();
    }
}
