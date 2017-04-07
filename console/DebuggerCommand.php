<?php

namespace Dephpug\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dephpug\Dephpugger;
use Dephpug\Exception\ExitProgram;
use Dephpug\Config;

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
        $config = Config::getInstance();
        $projectPath = getcwd();
        $phpPath = $config->server['phpPath'];
        $defaultPort = $config->server['port'];
        $defaultHost = $config->server['host'];
        $debuggerHost = $config->debugger['host'];
        $debuggerPort = $config->debugger['port'];
        $path = $config->server['path'] == null ? '' : $config->server['path'];
        $file = $config->server['file'] !== '' ? $path.$config->server['file'] : '';

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

        global $_PIDS;
        $pid = shell_exec($command.' > /dev/null & echo $!');
        $output->writeln('<options=bold>Starting pid: '.$pid.'</>');
        $_PIDS[] = $pid;
        unset($pid);

        $config = \Dephpug\Config::getInstance();
        \Dephpug\Readline::load($config->debugger['historyFile']);

        while (true) {
            try {
                $dephpugger = new Dephpugger();
                $dephpugger->start();
            } catch (ExitProgram $e) {
                $output->writeln("<fg=red;options=bold>{$e}</>");
                if ($e->getCode() == 2) {
                    continue;
                }
                exit(1);
            }
        }
    }
}

$application->add(new DebuggerCommand());
