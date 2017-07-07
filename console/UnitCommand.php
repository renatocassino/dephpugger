<?php

namespace Dephpug\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dephpug\Config;
use Dephpug\Dephpugger;

class UnitCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('unit')
            // the short description shown while running "php bin/console list"
            ->setDescription('Run the phpunit with dephpugger')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Run the command phpunit with dephpugger');
    }

    protected function execute(InputInterface $_, OutputInterface $output)
    {
        $config = new Config();
        $config->configure();
        $projectPath = getcwd();
        $defaultPort = $config->server['port'];
        $defaultHost = $config->server['host'];
        $debuggerHost = $config->debugger['host'];
        $debuggerPort = $config->debugger['port'];
        $path = $config->server['path'] == null ? '' : $config->server['path'];
        $file = $config->server['file'] !== '' ? $path.$config->server['file'] : '';

        $phpPath = '';
        die(VENDORDIR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'phpunit');

        $pathWithParam = $path != '' ? "-t $path" : '';

        $command = "{$phpPath} -S {$defaultHost}:{$defaultPort} ";
        $command .= "-t {$projectPath} ";
        $command .= '-dxdebug.remote_enable=1 -dxdebug.remote_mode=req ';
        $command .= "-dxdebug.remote_port={$debuggerPort} ";
        $command .= "-dxdebug.remote_host={$debuggerHost} -dxdebug.remote_connect_back=0 ";
        $command .= "{$pathWithParam} {$file}";

        $output->write(splashScreen());
        $output->writeln("Running command: <fg=red>{$command}</>\n");
        $output->writeln("Access in <comment>{$defaultHost}:{$defaultPort}</comment>\n");

        shell_exec($command);
    }
}

$application->add(new UnitCommand());
