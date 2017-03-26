<?php

namespace Dephpug;

/**
 * Class to parse messages in DBGP protocol.
 */
class MessageParse
{
    /**
     * Method to get xml from DBGP and return
     * range of lines in a file.
     *
     * @param string $message String xml with info of file
     *
     * @return array With first index as a filename and second as a line
     */
    public function getFileAndLine($message)
    {
        $hasFileNo = preg_match('/lineno="(\d+)"/', $message, $fileno);
        $pattern = '/filename="file:\/\/([^\"]+)"/';
        $hasFilename = preg_match($pattern, $message, $filename);

        if ($hasFileNo && $hasFilename) {
            return [$filename[1], $fileno[1]];
        }

        return null;
    }

    /**
     * Check if text starts with a string.
     *
     * @param string $text    a text
     * @param string $pattern a string to check
     *
     * @return bool
     */
    public function startsWith($text, $pattern)
    {
        $slen = strlen($pattern);

        return $slen === 0 || strncmp($text, $pattern, $slen) === 0;
    }

    /**
     * Check if status is for stop if file ended.
     *
     * @param string $response String with xml from DBGP
     *
     * @return bool
     */
    public function isStatusStop($response)
    {
        return (bool) preg_match('/status=\"stopp(?:ed|ing)\"/', $response);
    }

    /**
     * Format to make message compatible with parse xml.
     *
     * When dephpugger receive the xml, there is any invalid chars
     * and start with code (three ints). This method remove this
     * to make compatible with `simplexml_load_string`
     *
     * @param string $message String with xml from DBGP
     *
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
     * @param string $xml String with xml format
     *
     * @return string
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

    /**
     * Check if receive an error from DBGP.
     *
     * @param string $message String with xml from DBGP
     * @param array  $errors  Pointer to return errors
     *
     * @return bool
     */
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

    /**
     * If message is a stream, print xml formated.
     *
     * @param string $response Xml message from DBGP
     *
     * @return string|null
     */
    public function printIfIsStream($response)
    {
        // This is hacky, but it works in all cases and doesn't require parsing xml.
        $prefix = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<stream";
        $isStream = $this->startsWith($response, $prefix);

        // Echo back the response to the user if it isn't a stream.
        if (!$isStream) {
            try {
                $responseParsed = $this->xmlBeautifier($response);

                return "<comment>{$responseParsed}</comment>\n";
            } catch (\Symfony\Component\Console\Exception\InvalidArgumentException $e) {
                return "\n\n{$response}\n\n";
            }
        }

        return null;
    }
}
