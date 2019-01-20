<?php

namespace Dephpug\Command;

use Dephpug\Output;

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
        return implode(
            ' ',
            [
            'This command will get the current file and show the next lines.',
            "For example, if you have the :\n\n",
            "=> 2. echo 'This ';\n    3. echo 'is ';\n\n",
            "Will show the next lines in file.\n\n",
            "   4. echo 'my ';\n    5. echo 'code!';",
            ]
        );
    }

    public function getAlias()
    {
        return 'l / list';
    }

    public function getRegexp()
    {
        return '/^l(?:ist)?$/i';
    }

    public function exec()
    {
        $lineToRange = $this->core->filePrinter->lineToRange;
        $totalLines = $this->core->filePrinter->numberOfLines();
        $offset = $this->core->filePrinter->offset;
        $lineToRange = min($totalLines - $offset, $lineToRange + $offset);
        $this->core->filePrinter->lineToRange = $lineToRange;

        Output::print($this->core->filePrinter->showFile());
    }
}
