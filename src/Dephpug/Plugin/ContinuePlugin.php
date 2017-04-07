<?php

namespace Dephpug\Plugin;

class ContinuePlugin extends \Dephpug\Plugin
{
    public function getName()
    {
        return 'Continue';
    }

    public function convertCommand($line, $transactionId)
    {
        if(preg_match('/^c(?:ontinue)?/i', $line)) {
            $command = sprintf('run -i %s', $transactionId);
            $this->dbgpServer->sendCommand($command);
        }
    }
}
