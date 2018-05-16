<?php

namespace Dephpug\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dephpug\Config;
use Dephpug\Dephpugger;

use function Dephpug\SplashScreen;

class ServerCommand extends Command
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

    protected function execute(InputInterface $_, OutputInterface $output)
    {
        $config = new Config();
        $config->configure();
        $projectPath = getcwd();
        $phpPath = PHP_BINARY;
        $defaultPort = $config->server['port'];
        $defaultHost = $config->server['host'];
        $debuggerHost = $config->debugger['host'];
        $debuggerPort = $config->debugger['port'];
        $path = $config->server['path'] == null ? '' : $config->server['path'];
        $file = $config->server['file'] == null ? '' : $config->server['file'];

        $pathWithParam = $path != '' ? "-t $path" : '';

        $command = "{$phpPath} -S {$defaultHost}:{$defaultPort} ";
        $command .= $pathWithParam !== '' ? $pathWithParam : "-t {$projectPath} ";
        $command .= ' -dxdebug.remote_enable=1 -dxdebug.remote_mode=req ';
        $command .= " -dxdebug.remote_port={$debuggerPort} ";
        $command .= " -dxdebug.remote_host={$debuggerHost} -dxdebug.remote_connect_back=0 ";
        $command .= $path !== '' && $file !== '' ? $path.$file : '';

        $output->write(splashScreen());
        $output->writeln("Running command: <fg=red>{$command}</>\n");
        $output->writeln("Access in <comment>{$defaultHost}:{$defaultPort}</comment>\n");

        shell_exec($command);
    }
}
