<?php

namespace Dephpug\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InfoCommand extends Command
{
    private $output;

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('info')
            // the short description shown while running "php bin/console list"
            ->setDescription('Get your configuration about XDebug')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Command to get all configurations about XDebug');
    }

    protected function execute(InputInterface $_, OutputInterface $output)
    {
        $phpInfo = new concerns\PhpInfo();
        $printer = new concerns\Printer();

        $output->writeln("\n<comment>Checking your dependencies</comment>\n");

        $output->writeln($printer->requiredMessage($phpInfo->xdebugInstalled(), 'XDebug is installed.'));
        $output->writeln($printer->requiredMessage($phpInfo->xdebugIsActive(), 'XDebug is active.'));

        $output->writeln("\n<options=bold> -- Infos about PHP environment -- </>\n");
        $vars = $phpInfo->getVars("/\nxdebug\.(?<name>\w+) \=\> (?<value>.+)\n/");

        foreach ($vars[0] as $key => $_) {
            $output->writeln("xdebug.{$vars['name'][$key]}: <options=bold>{$vars['name'][$key]}</>");
        }
    }
}
