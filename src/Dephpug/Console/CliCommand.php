<?php

namespace Dephpug\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Dephpug\Config;
use Dephpug\Dephpugger;

class CliCommand extends Command
{
    private $output;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('cli')
            // the short description shown while running "php bin/console list"
            ->setDescription('Command to run php cli scripts with debugger')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Command to run php cli scripts connecting with dephpugger')
            ->addArgument('file', InputArgument::REQUIRED, 'The php file script');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Config();
        $config->configure();
        $phpPath = PHP_BINARY;
        $debuggerPort = $config->debugger['port'];
        $phpFile = $input->getArgument('file');

        $configVar = 'XDEBUG_CONFIG="idekey=PHPSTORM"';
        $command = "{$configVar} {$phpPath} -dxdebug.remote_enable=1 -dxdebug.remote_mode=req -dxdebug.remote_port={$debuggerPort} -dxdebug.remote_host=127.0.0.1 {$phpFile}";

        $output->writeln("Running file script: <options=bold>{$phpFile}</>");
        $output->writeln("Command: {$command}");

        passthru($command);
    }
}
