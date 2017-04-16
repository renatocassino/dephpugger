<?php

namespace Dephpug\Parser;

use Dephpug\MessageEvent as MessageParse;
use Dephpug\FilePrinter;
use Dephpug\Output;

class FilePrinterMessageEvent extends MessageParse
{
    public $fileNumber;
    public $fileName;

    public function match(string $xml)
    {
        $hasFileNo = preg_match('/lineno="(\d+)"/', $xml, $fileNumber);
        $pattern = '/filename="file:\/\/([^\"]+)"/';
        $hasFilename = preg_match($pattern, $xml, $fileName);

        if ($hasFileNo && $hasFilename) {
            $this->fileNumber = $fileNumber[1];
            $this->fileName = $fileName[1];

            return true;
        }

        return false;
    }

    public function exec()
    {
        $this->core->filePrinter->setFilename($this->fileName);
        $this->core->filePrinter->line = $this->fileNumber;
        $this->core->filePrinter->lineToRange = $this->fileNumber;
        Output::print($this->core->filePrinter->showFile());
    }
}
