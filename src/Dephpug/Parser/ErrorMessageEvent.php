<?php

namespace Dephpug\Parser;

use Dephpug\MessageEvent as MessageParse;
use Dephpug\Output;

/**
 * Event to get the error message if DBGP cannot run the previous command.
 * The behaviour is only print an error red message.
 *
 * @example <?xml version="1.0" encoding="iso-8859-1"?>
 * <response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="property_get" transaction_id="1" status="break" reason="ok">
 *  <error code="300">
 *   <message>
 *    <![CDATA[can not get property]]>
 *   </message>
 *  </error>
 * </response>
 */
class ErrorMessageEvent extends MessageParse
{
    /**
     * Error returned from DBGP
     */
    public $message;

    /**
     * Error code returned from DBGP
     */
    public $code;

    /**
     * Trying match checking if has tag error
     *
     * @param  string $xml
     * @return bool
     */
    public function match(string $xml)
    {
        $xml = @simplexml_load_string($xml);
        if (isset($xml->error)) {
            $this->message = (string) $xml->error->message;
            $this->code = (int) $xml->error['code'];

            return true;
        }

        return false;
    }

    /**
     * Printing error message
     *
     * @return void
     */
    public function exec()
    {
        Output::print("<fg=red;options=bold>Code: {$this->code} - {$this->message}</>");
    }
}
