<?php

namespace Dephpug\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dephpug\Dephpugger;
use Dephpug\Exception\ExitProgram;
use Dephpug\Config;


class BetaDebuggerCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('beta')
            // the short description shown while running "php bin/console list"
            ->setDescription('Start a debugger client to your application.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command start a websocket with your DBGp protocol to communicate with XDebug');
    }

    protected function execute(InputInterface $_, OutputInterface $output)
    {
        $dephpugger = new \Dephpug\Debug;
        $dephpugger->addPlugin(new \Dephpug\Plugin\ContinuePlugin());
        $config = \Dephpug\Config::getInstance();
        $dephpugger->host = $config->debugger['host'];
        $dephpugger->port = $config->debugger['port'];

        $dephpugger->run();
    }
}

$application->add(new BetaDebuggerCommand());
