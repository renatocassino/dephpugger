<?php

namespace Dephpug;

/**
 * Class that receive a filename and line to show part
 * of file in debugger with limits and format
 */
class FilePrinter
{
    /**
     * Array with lines in a file
     */
    public $file;

    /**
     * Filename of $file (optional)
     */
    public $filename;

    /**
     * Current line of a file to indicate the arrow
     */
    public $line;

    /**
     * Line to show in print
     */
    public $lineToRange;

    /**
     * Number of lines to show above and bellow $lineToRange
     */
    public $offset = 6;

    /**
     * Reserved words in php to color
     */
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
        'foreach',
        'if',
        'else',
        'do',
        'while',
    ];

    /**
     * Boolean words
     */
    private $booleanWords = [
        'true',
        'false',
    ];

    /**
     * Consts reserved to color print
     */
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

    /**
     * Set filename and instantiate the attribute $file
     * with lines as array
     *
     * @param  string $filename
     * @return void
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        $this->file = file($filename);
    }

    /**
     * Set direct file as array of lines
     *
     * @param  array $file
     * @return void
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Set attribute offset
     *
     * @param int $offset
     */
    public function setOffset(int $offset)
    {
        $this->offset = (int) $offset;
    }

    /**
     * Get the pagination range considering $offset to get
     * lines above and bellow
     *
     * @param  int $line
     * @return array with indexes of pages, not lines
     */
    public function getRangePagination($line = 1)
    {
        $firstLine = max($line - $this->offset, 0);
        $lastLine = min($line + $this->offset, $this->numberOfLines() - 1);

        return [$firstLine, $lastLine];
    }

    /**
     * List lines around a setted line in file
     *
     * @param  int $line
     * @return string Indicates lines of a file
     */
    public function listLines($line = 1)
    {
        list($start, $end) = $this->getRangePagination($line);
        $lines = [];
        foreach (range($start, $end) as $line) {
            $lines[$line + 1] = $this->file[$line];
        }

        return $lines;
    }

    /**
     * Number of lines in the file
     *
     * @return int Indicates the number of the lines in the file setted
     */
    public function numberOfLines()
    {
        return count($this->file);
    }

    /**
     * Show file with full informations
     *
     * @return string
     */
    public function showFile()
    {
        $fileLines = $this->listLines($this->lineToRange);
        $fileToShow = '';

        $numberLines = array_keys($fileLines);
        $firstLine = $numberLines[0];
        $lastLine = array_reverse($numberLines)[0];

        // Message first
        $fileToShow .= "\n<fg=blue>[{$firstLine}:{$lastLine}] in file://{$this->filename}:{$this->line}</>\n";

        foreach ($fileLines as $currentLine => $content) {
            $isThisLineString = ($currentLine == $this->line) ? '<fg=magenta;options=bold>=> </>' : '   ';
            $content = $this->colorCode($content);
            $fileToShow .= "{$isThisLineString}<fg=yellow>{$currentLine}:</> <fg=white>{$content}</>";
        }

        return $fileToShow;
    }

    /**
     * Color code received to appear like an IDE changing reserved
     * words in PHP to different colors
     *
     * @param  string $content Indicates a php code
     * @return string
     */
    public function colorCode($content)
    {
        foreach ($this->reservedWords as $word) {
            $content = preg_replace("/\b$word\b/i", "<fg=blue>{$word}</>", $content);
        }

        foreach ($this->booleanWords as $word) {
            $content = preg_replace("/\b$word\b/i", "<fg=yellow;options=bold>{$word}</>", $content);
        }

        foreach ($this->consts as $word) {
            $content = str_replace($word, "<fg=red>{$word}</>", $content);
        }

        $content = preg_replace('/(\?\>)/', '<fg=red;options=bold>$1</>', $content);
        $content = preg_replace('/(<\?php)/', '<fg=red;options=bold>$1</>', $content);
        $content = preg_replace('/([\w_]+)\(/', '<fg=green;options=bold>$1</>(', $content);
        $content = preg_replace('/(\".+\")/', '<fg=green>$1</>', $content);
        $content = preg_replace('/(\'.+\')/', '<fg=green>$1</>', $content);
        $content = preg_replace('/(\$[\w]+)/', '<fg=cyan>$1</>', $content);

        return $content;
    }
}
