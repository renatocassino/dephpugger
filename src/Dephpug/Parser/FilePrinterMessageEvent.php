<?php

namespace Dephpug\Parser;

use Dephpug\MessageEvent as MessageParse;
use Dephpug\FilePrinter;
use Dephpug\Output;

/**
 * Event to get the filename and line number to print in the screen.
 *
 * @example <?xml version="1.0" encoding="iso-8859-1"?>
 *  <response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="run" transaction_id="0" status="break" reason="ok">
 *    <xdebug:message filename="file:///path/of/file.php" lineno="10"></xdebug:message>
 *  </response>
 */
class FilePrinterMessageEvent extends MessageParse
{
    /**
     * Line number of the file
     */
    public $fileNumber;

    /**
     * Name of the file to print
     */
    public $fileName;

    /**
     * Trying to match if have filename and number
     *
     * @param  string $xml
     * @return void
     */
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

    /**
     * Using the class *\Dephpug\FilePrinter* to show the file
     *
     * @uses   \Dephpug\FilePrinter
     * @return void
     */
    public function exec()
    {
        $this->core->filePrinter->offset = $this->core->config->debugger['lineOffset'];
        $this->core->filePrinter->setFilename($this->fileName);
        $this->core->filePrinter->line = $this->fileNumber;
        $this->core->filePrinter->lineToRange = $this->fileNumber;
        Output::print($this->core->filePrinter->showFile());
    }
}
