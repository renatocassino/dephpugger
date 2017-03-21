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

    public function formatXmlString($xml)
    {
        $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
        $token = strtok($xml, "\n");
        $result = '';
        $pad = 0;
        $matches = array();
        while ($token !== false) :
            if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
                $indent = 0; elseif (preg_match('/^<\/\w/', $token, $matches)) :
            $pad--;
        $indent = 0; elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
            $indent = 1; else :
            $indent = 0;
        endif;
        $line = str_pad($token, strlen($token) + $pad, ' ', STR_PAD_LEFT);
        $result .= $line."\n";
        $token = strtok("\n");
        $pad += $indent;
        endwhile;

        return $result;
    }

    public function isErrorMessage($message, &$errors = [])
    {
        $xml = simplexml_load_string($message);
        // Getting error messages
        if (isset($xml->error)) {
            $errors['message'] = (string) $xml->error->message;
            $errors['code'] = $xml->error['code'];

            return true;
        }

        return false;
    }
}
