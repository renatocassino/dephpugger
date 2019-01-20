<?php

namespace Dephpug\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
        $phpInfo = new concerns\PhpInfo();
        $printer = new concerns\Printer();

        $output->writeln("\n<comment>Checking your dependencies</comment>\n");

        $output->writeln($printer->requiredMessage($phpInfo->checkPHPVersion(), 'Your PHP version is 7.0 or more'));
        $output->writeln($printer->requiredMessage($phpInfo->xdebugInstalled(), 'XDebug is installed.'));
        $output->writeln($printer->requiredMessage($phpInfo->xdebugIsActive(), 'XDebug is active.'));

        $output->writeln("\n<options=bold> -- Infos about PHP environment -- </>\n");
        $output->writeln($phpInfo->printVar('xdebug.idekey', 'XDebug idekey'));
        $output->writeln($phpInfo->printVar('xdebug.cli_color', 'XDebug cli_color'));
        $output->writeln($phpInfo->printVar('xdebug.default_enable', 'XDebug default_enable'));
    }
}
