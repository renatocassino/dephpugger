<?php

namespace Dephpug;

use \Symfony\Component\Console\Output\ConsoleOutput;
use \Symfony\Component\Console\Formatter\OutputFormatter;

class FilePrinter
{
    public $file; // Array
    public $filename;
    public $offset = 6;

    public function setFilename($filename) {
        $this->filename = $filename;
        $this->file = file($filename);
    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function setOffset($offset) {
        $this->offset = (int) $offset;
    }

    /**
     * @return array with indexes of pages, not lines
     */
    public function getRangePagination($line=1) {
        $numberOfLines = count($this->file)-1;
        $firstLine = max($line-$this->offset, 0);
        $lastLine = min($line+$this->offset, $numberOfLines);

        return [$firstLine, $lastLine];
    }

    public function listLines($line=1) {
        list($start, $end) = $this->getRangePagination($line);
        $lines = [];
        foreach(range($start, $end) as $line) {
            $lines[$line+1] = $this->file[$line];
        }
        return $lines;
    }

    public function showFile($line=1) {
        //        ob_start();
        //$output = new ConsoleOutput();
        //$output->setFormatter(new OutputFormatter(true));
        //$output->writeln($this->unformatedShowFile($line));
        return $this->unformatedShowFile($line);
    }

    public function unformatedShowFile($line=1) {
        $fileLines = $this->listLines($line);
        $fileToShow = '';

        $numberLines = array_keys($fileLines);
        $firstLines = $numberLines[0];
        $lastLines = array_reverse($numberLines)[0];
        $fileToShow .= "\n[{$firstLine}:{$lastLine}] in file://{$this->filename}:{$line}\n";

        foreach($fileLines as $currentLine => $content) {
            $isThisLineString = ($currentLine == $line) ? '<comment>=> </comment>' : '   ';
            $fileToShow .= "{$isThisLineString}{$currentLine}: {$content}";
        }

        return $fileToShow;
    }
}