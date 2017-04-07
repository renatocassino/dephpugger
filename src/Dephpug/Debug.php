<?php

namespace Dephpug;

class Debug
{
    private $plugins = [];
    public $host;
    public $port;
    public $dbgpServer;
    public $state;

    public function __construct()
    {
        $this->dbgpServer = new DbgpServer();
    }

    public function addPlugin(iPlugin $plugin)
    {
        $plugin->dbgpServer = &$this->dbgpServer;
        $this->plugins[] = $plugin;
    }

    public function callPluginMethod($methodName, $params)
    {
        foreach($this->plugins as $plugin)
        {
            if (method_exists($plugin, $methodName)) {
                $returnMethod = $plugin->$methodName(...$params);
            }
        }
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