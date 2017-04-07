<?php

namespace Dephpug;

class Debug
{
    private $plugins = [];
    public $host;
    public $port;

    public function addPlugin(iPlugin $plugin)
    {
        $this->plugins[] = $plugin;
    }

    public function callPluginMethod($methodName, $params)
    {
        foreach($this->plugins as $plugin)
        {
            if (method_exists($plugin, $methodName)) {
                $returnMethod = $plugin->$methodName(...$params);
                if($returnMethod[0] == 'valid') {
                    return $returnMethod[1];
                }
            }
        }
    }

    public function run()
    {
        $dbgpServer = new DbgpServer();
        $dbgpServer->startClient($this->host, $this->port);
        $currentResponse = $dbgpServer->getResponse();

        while(true) {
            $line = Readline::readline();
            $command = $this->callPluginMethod('convertCommand', [$line, DbgpServer::getTransactionId()]);

            if($line == 'q') {break;}
        }
    }
}