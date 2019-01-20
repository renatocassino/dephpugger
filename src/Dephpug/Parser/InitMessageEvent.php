<?php

namespace Dephpug\Parser;

use Dephpug\MessageEvent as MessageParse;

/**
 * Event to get the first message when the request (or cli executation) start.
 *
 * @example <?xml version="1.0" encoding="iso-8859-1"?>
 *  <init xmlns="urn:debugger_protocol_v1" xmlns:xdebug="http://xdebug.org/dbgp/xdebug" fileuri="file:///path/of/index.php" language="PHP" xdebug:language_version="7.0.4-5ubuntu1" protocol_version="" appid=""><engine version=""><![CDATA[Xdebug]]></engine><author><![CDATA[Derick Rethans]]></author><url><![CDATA[http://xdebug.org]]></url><copyright><![CDATA[Copyright (c) 2002-2016 by Derick Rethans]]></copyright></init>
 */
class InitMessageEvent extends MessageParse
{
    /**
     * Trying match searching string *init*
     *
     * @param  string $xml
     * @return void
     */
    public function match(string $xml)
    {
        return (bool) preg_match('/\<init/', $xml);
    }

    /**
     * Call continue in client
     *
     * @return void
     */
    public function exec()
    {
        $this->core->dbgpClient->continue();
    }
}
