<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dephpug\Config;
use Dephpug\Dephpugger;

class RequirementsCommand extends Command
{
    private $output;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('requirements')
            // the short description shown while running "php bin/console list"
            ->setDescription('Command to list requirements to run dephpugger')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Command to check if all dependencies are ok');
    }

    protected function execute(InputInterface $_, OutputInterface $output)
    {
        $this->output = $output;

        $this->output->writeln("\n<comment>Checking your dependencies</comment>\n");

        $this->writeMessage($this->checkPHPVersion(), "You PHP Version is 7.0 or more.");
        $this->writeMessage($this->xdebugInstalled(), "XDebug is installed.");
        $this->writeMessage($this->xdebugIsActive(), "XDebug is active.");

        $this->output->writeln("\n");
    }

    private function writeMessage($success, $message) {
        $emoji = $this->getEmoji($success);
        $this->output->writeln("  {$emoji} {$message}");
    }

    private function getEmoji($success) {
        return ($success)
            ? '<fg=green;options=bold>✔️</>️'
            : '<fg=red;options=bold>✖️</>';
    }

    private function checkPHPVersion() {
        return ((int)phpversion()[0] >= 7);
    }

    private function xdebugInstalled() {
        return extension_loaded('xdebug');
    }

    private function xdebugIsActive() {
        if($this->xdebugInstalled()) {
            return xdebug_is_enabled();
        }
        return false;
    }
}

$application->add(new RequirementsCommand());
