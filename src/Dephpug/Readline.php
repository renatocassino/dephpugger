<?php

namespace Dephpug;

class Readline
{
    private static $loaded;
    private static $historyFile;
    private static $lastLine;

    public static function getHistoryFile()
    {
        if(!self::$historyFile) {
            $config = Config::getInstance();
            self::$historyFile = $config->debugger['historyFile'];
        }
        return self::$historyFile;
    }
    
    public static function load()
    {
        if(!self::$loaded) {
            if(file_exists(self::getHistoryFile())) {
                readline_read_history(self::getHistoryFile());
            }
            self::$loaded = true;
        }
    }

    public static function readline()
    {
        self::load();
        $line = readline('(dbgp) => ');
        if($line !== self::$lastLine) {
            readline_add_history($line);
            readline_write_history(self::getHistoryFile());
            self::$lastLine = $line;
        }
        return $line;
    }
}