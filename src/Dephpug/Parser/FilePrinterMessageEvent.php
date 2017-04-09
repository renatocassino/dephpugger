<?php

namespace Dephpug\Parse;

use Dephpug\MessageEvent as MessageParse;

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
        echo 'HERE I\'m PRINTING FILE' . $this->fileNumber . ' - ' . $this->fileName;
    }
}
