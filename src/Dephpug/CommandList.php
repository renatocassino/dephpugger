<?php

namespace Dephpug;

/**
 * Class with reflection to get all commands in Dephpugger project and
 * run methods following the level in each command
 */
class CommandList
{
    /**
     * Reflection to get all commands
     */
    public $reflection;

    /**
     * Pointer to core instance
     */
    public $core;

    public function __construct(&$core)
    {
        $this->reflection = new PluginReflection($core);
        $this->core = $core;
    }

    /**
     * Match all commands regexp and return the correct
     * command to match this one (sorted by level)
     *
     * @param  string $command
     * @return obj $plugin
     */
    public function match($command)
    {
        $plugins = $this->reflection->getPlugins();
        usort(
            $plugins,
            function ($a, $b) {
                return $a->level > $b->level;
            }
        );

        foreach ($plugins as $plugin) {
            if ($plugin->match($command)) {
                return $plugin;
            }
        }
    }

    /**
     * Get the command and call the method exec();
     *
     * @param  string $command
     * @return void
     */
    public function run($command)
    {
        $command = $this->match($command);
        if ($command) {
            $command->exec();
        }
    }

    /**
     * Run an especific method for a reflection passing parameters
     *
     * @param string $methodName Indicates the name of the method to call
     * @param array  $params     Indicates the parameters to send to method called
     */
    public function runMethod($methodName, $params = [])
    {
        $plugins = $this->reflection->getPlugins();

        foreach ($plugins as $plugin) {
            if (method_exists($this, $methodName)) {
                $plugin->$methodName(...$params);
            }
        }
    }
}
