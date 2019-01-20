<?php

namespace Dephpug\Command;

class StepIntoCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'Step';
    }

    public function getShortDescription()
    {
        return 'Step into in breakpoint';
    }

    public function getDescription()
    {
        return implode(
            ' ',
            [
            'This command will get inside the method/function or next line if this one doesn\' exist.',
            "For example, if you have this code:\n\n",
            "=> 2. functionCall();\n    3. \$var = 1;\n\n",
            "Instead of get the next line, you\'ll get inside the method\n\n",
            "   33. function functionCall() {\n => 34.   echo \"Your function here.\";\n    35. }",
            ]
        );
    }

    public function getAlias()
    {
        return 's / step_into';
    }

    public function getRegexp()
    {
        return '/^s(?:tep_into)?$/i';
    }

    public function exec()
    {
        $this->core->dbgpClient->stepInto();
    }
}
