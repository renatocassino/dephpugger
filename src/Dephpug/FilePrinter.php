<?php

namespace Dephpug;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;

class FilePrinter
{
    public $file; // Array
    public $filename;
    public $offset = 6;
    public $config;
    private $reservedWords = [
        '__halt_compiler',
        'array',
        'die',
        'echo',
        'empty',
        'eval',
        'exit',
        'include',
        'include_once',
        'isset',
        'list',
        'print',
        'require',
        'require_once',
        'return',
        'unset',
        'function',
        'for',
        'if',
        'else',
        'do',
        'while',
    ];

    private $consts = [
        '__CLASS__',
        '__DIR__',
        '__FILE__',
        '__FUNCTION__',
        '__LINE__',
        '__METHOD__',
        '__NAMESPACE__',
        '__TRAIT__',
    ];

    public function setFilename($filename)
    {
        $this->filename = $filename;
        $this->file = file($filename);
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function setOffset($offset)
    {
        $this->offset = (int) $offset;
    }

    /**
     * @return array with indexes of pages, not lines
     */
    public function getRangePagination($line = 1)
    {
        $numberOfLines = count($this->file) - 1;
        $firstLine = max($line - $this->offset, 0);
        $lastLine = min($line + $this->offset, $numberOfLines);

        return [$firstLine, $lastLine];
    }

    public function listLines($line = 1)
    {
        list($start, $end) = $this->getRangePagination($line);
        $lines = [];
        foreach (range($start, $end) as $line) {
            $lines[$line + 1] = $this->file[$line];
        }

        return $lines;
    }

    public function showFile($line = 1)
    {
        $output = new ConsoleOutput();
        $output->setFormatter(new OutputFormatter(true));
        $output->writeln($this->unformatedShowFile($line));
    }

    public function unformatedShowFile($line = 1)
    {
        $fileLines = $this->listLines($line);
        $fileToShow = '';

        $numberLines = array_keys($fileLines);
        $firstLine = $numberLines[0];
        $lastLine = array_reverse($numberLines)[0];

        // Message first
        $fileToShow .= "\n<fg=blue>[{$firstLine}:{$lastLine}] in file://{$this->filename}:{$line}</>\n";

        foreach ($fileLines as $currentLine => $content) {
            $isThisLineString = ($currentLine == $line) ? '<fg=magenta;options=bold>=> </>' : '   ';
            $content = $this->colorCode($content);
            $fileToShow .= "{$isThisLineString}<fg=yellow>{$currentLine}:</> <fg=white>{$content}</>";
        }

        return $fileToShow;
    }

    public function colorCode($content)
    {
        foreach ($this->reservedWords as $word) {
            $content = str_replace($word, "<fg=blue>{$word}</>", $content);
        }

        foreach ($this->consts as $word) {
            $content = str_replace($word, "<fg=red>{$word}</>", $content);
        }

        $content = preg_replace('/([\w_]+)\(/', '<fg=green;options=bold>$1</>(', $content);
        $content = preg_replace('/(\".+\")/', '<fg=green>$1</>', $content);
        $content = preg_replace('/(\'.+\')/', '<fg=green>$1</>', $content);
        $content = preg_replace('/(\$[\w]+)/', '<fg=cyan>$1</>', $content);

        return $content;
    }

    public function printValue($message)
    {
        $xml = simplexml_load_string($message);

        // Getting error messages
        if (isset($xml->error)) {
            $message = (string) $xml->error->message;

            return "<fg=red;options=bold>Error code: {$xml->error['code']} - {$message}</>";
        }

        // Getting value
        $command = (string) $xml['command'];
        if ('eval' === $command || 'property_get' === $command) {
            $typeVar = (string) $xml->property['type'];

            if ($typeVar == 'string') {
                $content = base64_decode((string) $xml->property);
            } elseif ('array' === $typeVar) {
                $data = $this->getArrayFormat($xml->property);
                $content = PHP_EOL.json_encode($data, JSON_PRETTY_PRINT);
            } elseif ('object' === $typeVar) {
                $typeVar .= " {$xml->property['classname']}";
                $data = $this->getObjectFormat($xml->property);
                $content = PHP_EOL.json_encode($data, JSON_PRETTY_PRINT);
            } else {
                // If string, float or another
                $content = (string) $xml->property;
            }

            return " => ({$typeVar}) {$content}\n\n";
        }
    }

    private function getObjectFormat($elements)
    {
        $content = [];
        foreach ($elements->children() as $el) {
            $type = 'null' === (string) $el['type'] ? 'method' : $el['type'];
            $value = 'base64' === (string) $el['encoding']
                   ? base64_decode((string) $el)
                   : (string) $el;
            if ('' !== $value) {
                $value = ' => '.$value;
            }

            $currentContent = "({$type}) `{$el['facet']}`{$value}";

            $key = (string) $el['name'];
            if ('method' === $type) {
                $key .= '()';
            }
            $content[$key] = $currentContent;
        }

        return $content;
    }

    private function getArrayFormat($elements)
    {
        $data = [];
        foreach ($elements->children() as $child) {
            $key = (string) $child->attributes()['name'];

            switch ($child->attributes()['type']) {
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
