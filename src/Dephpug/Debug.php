<?php

namespace Dephpug;

class Debug
{
    public $host;
    public $port;
    public $dbgpServer;
    public $state;

    public function __construct()
    {
        $this->dbgpServer = new DbgpServer();
    }

    public function run()
    {
        $this->dbgpServer->startClient($this->host, $this->port);

        do {
            if($this->dbgpServer->hasMessage()) {
                $currentResponse = $this->dbgpServer->getResponse();
                echo $currentResponse;
                $this->callPluginMethod('receiveXmlMessage', [$currentResponse]);
                continue;
            }

            if(!$this->dbgpServer->hasMessage()) {
                $line = Readline::readline();
                $command = $this->callPluginMethod('convertCommand', [$line, DbgpServer::getTransactionId()]);

                if($line == 'q') {break;}
                continue;
            }
        } while(true);
    }
}