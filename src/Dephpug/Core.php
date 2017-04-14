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
        $this->filePrinter = new FilePrinter();
        $this->config = new Config();
        $this->config->configure();
    }

    public function run()
    {
        $host = $this->config->debugger['host'];
        $port = $this->config->debugger['port'];

        $this->dbgpServer->startClient($host, $port);
        $this->startRepl();
    }

    public function startRepl()
    {
        while (true) {
            if ($this->dbgpServer->hasMessage) {
                $currentResponse = $this->dbgpServer->getResponse();
                $this->parserList->run($currentResponse);
                continue;
            }

            $line = $this->readline->scan();
            $this->commandList->run($line);
        }
    }
}
