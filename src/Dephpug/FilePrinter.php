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
        $output = new ConsoleOutput();
        $output->setFormatter(new OutputFormatter(true));
        $output->writeln($this->unformatedShowFile($line));
    }

    public function unformatedShowFile($line=1) {
        $fileLines = $this->listLines($line);
        $fileToShow = '';

        $numberLines = array_keys($fileLines);
        $firstLine = $numberLines[0];
        $lastLine = array_reverse($numberLines)[0];

        // Message first
        $fileToShow .= "\n<fg=blue>[{$firstLine}:{$lastLine}] in file://{$this->filename}:{$line}</>\n";

        foreach($fileLines as $currentLine => $content) {
            $isThisLineString = ($currentLine == $line) ? '<fg=magenta;options=bold>=> </>' : '   ';
            $content = $this->colorCode($content);
            $fileToShow .= "{$isThisLineString}<fg=yellow>{$currentLine}:</> <fg=white>{$content}</>";
        }
        return $fileToShow;
    }

    public function colorCode($content) {
        $reservedWords = ["__halt_compiler","array","die","echo","empty","eval","exit","include","include_once","isset","list","print","require","require_once","return","unset", 'function', 'for', 'if', 'else', 'do', 'while'];
        foreach($reservedWords as $word) {
            $content = str_replace($word, "<fg=blue>{$word}</>", $content);
        }

        $consts = ['__CLASS__', '__DIR__', '__FILE__', '__FUNCTION__', '__LINE__', '__METHOD__', '__NAMESPACE__', '__TRAIT__'];
        foreach($consts as $word) {
            $content = str_replace($word, "<fg=red>{$word}</>", $content);
        }

        $content = preg_replace('/([\w_]+)\(/', '<fg=green;options=bold>$1</>(', $content);
        $content = preg_replace('/(\".+\")/', '<fg=green>$1</>', $content);
        $content = preg_replace('/(\'.+\')/', '<fg=green>$1</>', $content);
        $content = preg_replace('/(\$[\w]+)/', '<fg=cyan>$1</>', $content);
        return $content;
    }

    public function printFileByMessage($message)
    {
        $message = preg_replace('/^\d+/', '', $message);
        $message = str_replace("\00", '', $message);
        $xml = @simplexml_load_string('/tmp/' . $id, 'SimpleXMLElement', \LIBXML_NOWARNING);

        preg_match('/lineno="(\d+)"/', $message, $fileno);
        preg_match('/filename="file:\/\/([^\"]+)"/', $message, $filename);

        // Getting  lines
        if(count($fileno) > 1 && count($filename) > 1) {
            $filePrinter = new FilePrinter();
            $filePrinter->setFilename($filename[1]);
            $filePrinter->showFile($fileno[1]);
            return;
        }

        // Getting value
        if(preg_match('/command=\"property_get\"/', $message)) {
            preg_match('/\<\!\[CDATA\[(.+)\]\]\>/', $message, $value);
            preg_match('/type=\"([\w_-]+)\"/', $message, $type);

            if('array' === $type[1]) {
                $xml = simplexml_load_string($message);
                $data = $this->getArrayFormat($xml->property);
                $content = PHP_EOL . json_encode($data, JSON_PRETTY_PRINT);
            } else {
                $content = (preg_match('/encoding="base64"/', $message))
                         ? base64_decode($value[1])
                         : (string) $value[1];
            }

            $typeVar = $type[1];
            if($typeVar == 'object') {
                preg_match('/classname="([^\"]+)"/', $message, $nameClass);
                $typeVar .= " {$nameClass[1]}";
            }

            echo " => ({$typeVar}) {$content}\n\n";
        }
    }

    private function getArrayFormat($elements)
    {
        $data = [];
        foreach($elements->children() as $child) {
            $key = (string) $child->attributes()['name'];

            switch($child->attributes()['type'])
            {
            case 'int':
            case 'float':
                $data[$key] = $child->__toString(); break;
            case 'string':
                $data[$key] = base64_decode($child->__toString()); break;
            case 'array':
                 $data[$key] = '(array) [...]';
            }
        }
        return $data;
    }
}
