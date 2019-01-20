<?php

namespace Dephpug;

/**
 * Class with reflection to get all messages parse in project
 */
class MessageParseList
{
    /**
     * Reflection to get all message parses
     */
    public $reflection;

    /**
     * Pointer to core instance
     */
    public $core;

    public function __construct(&$core)
    {
        $this->reflection = new PluginReflection($core, 'Dephpug\Interfaces\iMessageEvent');
        $this->core = $core;
    }

    /**
     * Get all plugins that match with a xml
     *
     * @param  string $xml
     * @return array Indicates the list of plugins matched with xml
     */
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

    /**
     * Run all methods in MessageParse for all plugins that match the xml
     *
     * @param  string $xml
     * @return void
     */
    public function run($xml)
    {
        $messageParses = $this->match($xml);
        foreach ($messageParses as $messageParse) {
            $messageParse->exec();
        }
    }
}
