<?php

namespace Dephpug;

use Dephpug\Dbgp\Client;

class Core
{
    public function __construct()
    {
        $this->commandList = new CommandList($this);
        $this->parserList = new MessageParseList($this);
        $this->readline = new Readline();
        $this->dbgpClient = new Client();
        $this->filePrinter = new FilePrinter();
        $this->config = new Config();
        $this->config->configure();
    }

    public function run()
    {
        $host = $this->config->debugger['host'];
        $port = $this->config->debugger['port'];

        $this->dbgpClient->startClient($host, $port);
        $this->startRepl();
    }

    public function startRepl()
    {
        while (true) {
            if ($this->dbgpClient->hasMessage()) {
                $currentResponse = $this->dbgpClient->getResponse();
                $this->parserList->run($currentResponse);
                continue;
            }

            $line = $this->readline->scan();
            $this->commandList->run($line);
        }
    }
}
