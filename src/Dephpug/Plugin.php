<?php

namespace Dephpug;

interface iPlugin
{
    public function getName();
}

class Plugin implements iPlugin
{
    public $dbgpServer;
    public function getName()
    {
        return 'Not implemented plugin';
    }

    public function receiveXml() {

    }
}