<?php
/*
namespace Dephpug\Printer;

use Dephpug\Exporter\iExporter;

class Printer implements iExporter;
{
    private $filePrinter;
    private $valuePrinter;

    public function __construct()
    {
        $this->filePrinter = new FilePrinter();
        $this->valuePrinter = new ValuePrinter();
    }

    public function printFileByMessage($message)
    {
        $message = $this->formatMessage($message);
        $hasFileNo = preg_match('/lineno="(\d+)"/', $message, $fileno);
        $hasFilename = preg_match('/filename="file:\/\/([^\"]+)"/', $message, $filename);

        // Getting  lines
        if ($hasFileNo && $hasFilename) {
            $this->setFilename($filename[1]);

            return $this->unformatedShowFile($fileno[1]);
        }

        return null;
    }
}
*/