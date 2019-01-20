<?php

namespace Dephpug\Command;

use Dephpug\Output;

class HelpCommand extends \Dephpug\Command
{
    public function getName()
    {
        return 'Help';
    }

    public function getShortDescription()
    {
        return 'You can run `help <commandName>` to show all explanation';
    }

    public function getDescription()
    {
        return implode(
            ' ',
            [
            'This command is used to get help for a dephpugger usage or a define command.',
            'You need to use the command `help <commandName>` to see all informations about a command.',
            ]
        );
    }

    public function getAlias()
    {
        return 'h / help';
    }

    public function getRegexp()
    {
        return '/^h(?:elp)?( (?P<command>\w+))?$/i';
    }

    public function exec()
    {
        if (isset($this->match['command'])) {
            return $this->renderHelpCommand();
        }

        return $this->helpDefault();
    }

    public function renderHelpCommand()
    {
        foreach ($this->core->commandList->reflection->getPlugins() as $command) {
            if (strtolower($command->getName()) === $this->match['command']) {
                $bigDescription = $command->getBigDescription();
                Output::print($bigDescription);

                return;
            }
        }

        Output::print("<fg=red;options=bold>Not found command `{$this->match['command']}`.</>");
    }

    public function helpDefault()
    {
        $content = <<<'EOL'

<info>-- Help command --</info>

<options=bold>Name         </>| <options=bold>Command             </>|<options=bold> Short Description</>
-------------+---------------------+---------------------

EOL;

        foreach ($this->core->commandList->reflection->getPlugins() as $command) {
            $alias = $this->getCharsWithSpaces($command->getAlias(), 20);
            $name = $this->getCharsWithSpaces($command->getName(), 12);
            $shortDescription = $command->getShortDescription();
            $content .= "{$name} | <comment>{$alias}</comment>| {$shortDescription}\n";
        }

        $content .= <<<'EOL'

<options=bold>Get variables</>

<fg=blue>Obs: You don't need use echo or var_dump/print_r. Use only the variable or function to get the value exported.</>
<fg=green>`$variable`      </>- Get value of variable
<fg=green>`my_function()`  </>- Get value of my_function()
<fg=green>`$variable = 33` </>- Set $variable to 33

Ex: <comment>`str_replace('a', 'b', 'blablabla')`</comment>
  => (string) blbblbblb

To get more information about a command, run: `help <nameOfCommand>`.
Ex: <options=bold>help Next</>

EOL;

        Output::print($content);
    }

    public function getCharsWithSpaces($word, $numberOfSpaces = 30)
    {
        $spacesToAdd = $numberOfSpaces - strlen($word);

        return $word.str_repeat(' ', $spacesToAdd);
    }
}
