<?php

namespace Dephpug;

/**
 * Class to parse messages in DBGP protocol.
 */
class MessageParse
{
    /**
     * Format to make message compatible with parse xml.
     *
     * When dephpugger receive the xml, there is any invalid chars
     * and start with code (three ints). This method remove this
     * to make compatible with `simplexml_load_string`
     *
     * @param  string $message String with xml from DBGP
     * @return string Format xml
     */
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

    /**
     * Method to get a xml and beautifier to print formated.
     *
     * @param  string $xml String with xml format
     * @return string Indicates the same xml, but formatted
     */
    public function xmlBeautifier($xml)
    {
        $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
        $token = strtok($xml, "\n");
        $result = '';
        $pad = 0;
        $matches = array();
        while ($token !== false) {
            if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) {
                $indent = 0;
            } elseif (preg_match('/^<\/\w/', $token, $matches)) {
                --$pad;
                $indent = 0;
            } elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) {
                $indent = 1;
            } else {
                $indent = 0;
            }
            $line = str_pad($token, strlen($token) + $pad, ' ', STR_PAD_LEFT);
            $result .= $line."\n";
            $token = strtok("\n");
            $pad += $indent;
        }

        return $result;
    }
}
