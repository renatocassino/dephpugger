<?php

namespace Dephpug;

class FilePrinter
{
    public $file; // Array
    public $filename;
    public $line;
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
        $firstLine = max($line - $this->offset, 0);
        $lastLine = min($line + $this->offset, $this->numberOfLines() - 1);

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

    public function numberOfLines()
    {
        return count($this->file);
    }

    public function showFile($arrow = true)
    {
        $fileLines = $this->listLines($this->line);
        $fileToShow = '';

        $numberLines = array_keys($fileLines);
        $firstLine = $numberLines[0];
        $lastLine = array_reverse($numberLines)[0];

        // Message first
        $fileToShow .= "\n<fg=blue>[{$firstLine}:{$lastLine}] in file://{$this->filename}:{$this->line}</>\n";

        foreach ($fileLines as $currentLine => $content) {
            $isThisLineString = ($currentLine == $this->line && $arrow) ? '<fg=magenta;options=bold>=> </>' : '   ';
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
}
