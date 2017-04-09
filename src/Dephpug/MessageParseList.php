<?php

namespace Dephpug;

class MessageParseList
{
    public $reflection;
    public $core;

    public function __construct(&$core)
    {
        $this->reflection = new PluginReflection($core, 'Dephpug\iMessageEvent');
        $this->core = $core;
    }

    public function match($xml)
    {
        foreach($this->reflection->getPlugins() as $plugin)
        {
            if($plugin->match($xml)) {
                return $plugin;
            }
        }
    }

    public function run($xml)
    {
        $messageParse = $this->match($xml);
        if($messageParse) {
            $messageParse->exec();
        }
    }
}
