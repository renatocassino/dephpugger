<?php

namespace Dephpug\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dephpug\Dephpugger;
use Dephpug\Exception\ExitProgram;
use Dephpug\Config;

use function Dephpug\SplashScreen;

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
        $output->write(splashScreen());
        while(true) {
            try {
                $dephpugCore = new \Dephpug\Core();
                $dephpugCore->run();
            } catch(\Dephpug\Exception\QuitException $e) {
                $message = $e->getMessage();
                $output->writeln("<comment> --- {$message} --- </comment>");
            } catch(\Dephpug\Exception\ExitProgram $e) {
                $message = $e->getMessage();
                $output->writeln("<fg=red;options=bold> --- {$message} --- </>");
                break;
            }
        }
    }
}

$application->add(new DebuggerCommand());
