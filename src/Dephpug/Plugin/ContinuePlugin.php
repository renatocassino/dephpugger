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
            return ['valid', sprintf('run -i %s', $transactionId)];
        }

        return ['invalid'];
    }
}
