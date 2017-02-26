<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dephpug\Config;
use Dephpug\Dephpugger;

class ServeCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('server')
            // the short description shown while running "php bin/console list"
            ->setDescription('Create a server in using your config connecting with to dephpugger debug')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Create a server to run in localhost.');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = Config::getInstance();
        $projectPath = getcwd();
        $phpPath = $config->server['phpPath'];
        $defaultPort = $config->server['port'];
        $defaultHost = $config->server['host'];
        $debuggerPort = $config->debugger['port'];

        $command = "{$phpPath} -S {$defaultHost}:{$defaultPort} -t {$projectPath} -dxdebug.remote_enable=1 -dxdebug.remote_mode=req -dxdebug.remote_port={$debuggerPort} -dxdebug.remote_host=127.0.0.1 -dxdebug.remote_connect_back=0";

        $output->write(splashScreen());
        $output->writeln("Running command: <fg=red>{$command}</>\n");
        $output->writeln("Access in <comment>{$defaultHost}:{$defaultPort}</comment>\n");

        shell_exec($command);
    }
}

$application->add(new ServeCommand());
