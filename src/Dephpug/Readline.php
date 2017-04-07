<?php

namespace Dephpug;

class Readline
{
    private static $historyFile;
    private static $lastLine;

    public static function load($historyFile)
    {
        self::$historyFile = $historyFile;
        if (file_exists($historyFile)) {
            readline_read_history($historyFile);
        }
    }

    public static function readline()
    {
        $line = '';
        while ($line === '') {
            $line = trim(readline('(dbgp) => '));
            if ($line !== self::$lastLine) {
                readline_add_history($line);
                readline_write_history(self::$historyFile);
                self::$lastLine = $line;
            }
        }

        return $line;
    }
}
