<?php

namespace Dephpug\Parser;

use Dephpug\MessageEvent as MessageParse;

/**
 * Event to get the close request in DBGP protocol.
 * To close the flow an exception is call.
 *
 * @example <?xml version="1.0" encoding="iso-8859-1"?>
 * <response xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" command="run" transaction_id="1" status="stopping" reason="ok"></response>
 */
class CloseMessageEvent extends MessageParse
{
    /**
     * Trying match getting the string *stopping* or *stopped*
     *
     * @param  string $xml
     * @return bool
     */
    public function match(string $xml)
    {
        return (bool) preg_match('/status=\"stopp(?:ed|ing)\"/', $xml);
    }

    /**
     * Quitting the current execution
     *
     * @throws \Dephpug\Exception\QuitException
     * @return void
     */
    public function exec()
    {
        throw new \Dephpug\Exception\QuitException('Closing request');
    }
}
