<?php

namespace Dephpug\Command;

use Dephpug\Output;

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
        return implode(
            ' ',
            [
            'This command will get the current file and show the previous lines.',
            "For example, if you have the :\n\n",
            "=> 4. echo 'my ';\n    5. echo 'code!';\n\n",
            "Will show the previous lines in file.\n\n",
            "   2. echo 'This ';\n    3. echo 'is ';",
            ]
        );
    }

    public function getAlias()
    {
        return 'lp / list_previous';
    }

    public function getRegexp()
    {
        return '/^(lp|list_previous)?$/i';
    }

    public function exec()
    {
        $lineToRange = $this->core->filePrinter->lineToRange;
        $offset = $this->core->filePrinter->offset;
        $lineToRange = max($offset, $lineToRange - $offset);
        $this->core->filePrinter->lineToRange = $lineToRange;

        Output::print($this->core->filePrinter->showFile());
    }
}
