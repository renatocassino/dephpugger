<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dephpug\DbgpServer;
use Dephpug\Exception\ExitProgram;

class DebuggerCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('debugger')
            // the short description shown while running "php bin/console list"
            ->setDescription('Start a debugger client to your application.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command start a websocket with your DBGp protocol to communicate with XDebug');
    }

    protected function execute(InputInterface $_, OutputInterface $output)
    {
        $output->writeln(splashScreen('Debugger'));
        while(true) {
            try {
                DbgpServer::start($output);
            } catch(ExitProgram $e) {
                $output->writeln("<fg=red;options=bold>{$e}</>");
                if($e->getCode() == 2) { continue; }
                exit(1);
            }
        }
    }
}

$application->add(new DebuggerCommand());
