<?php

namespace Dephpug;

class MessageParse
{
    public function getFileAndLine($message)
    {
        $hasFileNo = preg_match('/lineno="(\d+)"/', $message, $fileno);
        $hasFilename = preg_match('/filename="file:\/\/([^\"]+)"/', $message, $filename);

        if ($hasFileNo && $hasFilename) {
            return [$filename[1], $fileno[1]];
        }

        return null;
    }

    public function formatMessage($message)
    {
        // Remove # of bytes + null characters.
        $message = str_replace("\0", '', $message);
        $message = str_replace("\00", '', $message);
        $message = preg_replace("/^\d+?(?=<)/", '', $message);
        // Remove strings that could change between runs.
        $message = preg_replace('/appid="[0-9]+"/', 'appid=""', $message);
        $message = preg_replace('/engine version=".*?"/', 'engine version=""', $message);
        $message = preg_replace('/protocol_version=".*?"/', 'protocol_version=""', $message);
        $message = preg_replace('/ idekey=".*?"/', '', $message);
        $message = preg_replace('/address="[0-9]+"/', 'address=""', $message);

        return $message;
    }
}
