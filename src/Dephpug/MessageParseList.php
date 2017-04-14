<?php

namespace Dephpug;

class MessageParseList
{
    public $reflection;
    public $core;

    public function __construct(&$core)
    {
        $this->reflection = new PluginReflection($core, 'Dephpug\Interfaces\iMessageEvent');
        $this->core = $core;
    }

    public function match($xml)
    {
        $plugins = [];
        foreach ($this->reflection->getPlugins() as $plugin) {
            if ($plugin->match($xml)) {
                $plugins[] = $plugin;
            }
        }

        return $plugins;
    }

    public function run($xml)
    {
        $messageParses = $this->match($xml);
        foreach ($messageParses as $messageParse) {
            $messageParse->exec();
        }
    }
}
