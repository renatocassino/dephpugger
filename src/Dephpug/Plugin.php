<?php

namespace Dephpug;

interface iPlugin
{
    public function getName();
}

class Plugin implements iPlugin
{
    public function getName()
    {
        return 'Not implemented plugin';
    }

    public function receiveXml() {

    }
}