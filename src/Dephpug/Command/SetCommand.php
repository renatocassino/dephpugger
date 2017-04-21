<?php

namespace Dephpug\Command;

use Dephpug\Output;

class SetCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'Set';
    }

    public function getShortDescription()
    {
        return 'Set new config value';
    }

    public function getDescription()
    {
        return
            'You can change a config in debugger runner. '.PHP_EOL.
            'The commands are `lineOffset` and `verboseMode`.'.PHP_EOL.
            'The command is: set \<commandName\>: \<value\>'.PHP_EOL.
            'Ex: set verboseMode: false'
        ;
    }

    public function getAlias()
    {
        return 'set <prop>:<value>';
    }

    public function getRegexp()
    {
        return '/^set (\w+) *: *(\w+);?$/';
    }

    public function exec()
    {
        $prop = $this->match[1];
        $value = $this->match[2];

        if (!preg_match('/(verboseMode|lineOffset)/', $prop)) {
            Output::print("The prop `{$prop}` does not exist. You can change only `verboseMode` and `lineOffset`.");

            return false;
        }

        $this->core->config->setNewDebuggerValue($prop, $value);
        return true;
    }
}
