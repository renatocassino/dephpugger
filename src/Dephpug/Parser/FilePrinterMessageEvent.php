<?php

namespace Dephpug\Parse;

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
        $filePrinter = new FilePrinter();
        $filePrinter->setFilename($this->fileName);
        $filePrinter->line = $this->fileNumber;
        Output::print($filePrinter->showFile());
    }
}
