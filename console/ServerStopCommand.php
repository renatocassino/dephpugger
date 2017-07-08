<?php

namespace Dephpug\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dephpug\Config;
use Dephpug\Dephpugger;
use Dephpug\Runner\Server;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ServerStopCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('server:stop')
            // the short description shown while running "php bin/console list"
            ->setDescription('Create a server in using your config connecting with to dephpugger debug')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Create a server to run in localhost.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output instanceof ConsoleOutputInterface ? $output->getErrorOutput() : $output);
        $config = new Config();
        $config->configure();

        $server = new Server();
        $server->setConfig($config);
        $server->stop();

        $io->success('Server stopped');
    }
}

$application->add(new ServerStopCommand());
