<?php

namespace Dephpug;

class CommandList
{
    public $reflection;
    public $core;

    public function __construct(&$core)
    {
        $this->reflection = new PluginReflection($core);
        $this->core = $core;
    }

    public function match($command)
    {
        foreach($this->reflection->getPlugins() as $plugin)
        {
            if($plugin->match($command)) {
                return $plugin;
            }
        }
    }

    public function run($command)
    {
        $command = $this->match($command);
        if($command) {
            $command->exec();
        }
    }

    public function runMethod($methodName, $params=[])
    {
        $plugins = $this->reflection->getPlugins();

        foreach($plugins as $plugin)
        {
            if(method_exists($this, $methodName))
            {
                $plugin->$methodName(...$params);
            }
        }
    }
}
