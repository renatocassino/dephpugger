<?php

namespace Dephpug;

class Core
{
    public function __construct()
    {
        $this->commandList = new CommandList($this);
        $this->parserList = new MessageParseList($this);
        $this->readline = new Readline();
        $this->dbgpServer = new DbgpServer();
    }

    public function run()
    {
        $config = \Dephpug\Config::getInstance();

        $host = $config->debugger['host'];
        $port = $config->debugger['port'];

        $this->dbgpServer->startClient($host, $port);

        $this->startRepl();
    }

    public function startRepl()
    {
        while(true) {
            if($this->dbgpServer->hasMessage) {
                $currentResponse = $this->dbgpServer->getResponse();
                $this->parserList->run($currentResponse);
            }

            $line = $this->readline->scan();
            $this->commandList->run($line);

            if($line == 'q') {break;}
        }
    }
}
