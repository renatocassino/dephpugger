<?php

namespace Dephpug\Runner;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Bundle\WebServerBundle\WebServer;

use Dephpug\Output;

class Server extends Runner
{
    public function getServerHost()
    {
        $host = $this->config->server['host'];
        $port = $this->config->server['port'];
        return sprintf("%s:%s", $host, $port);
    }

    /**
     * 
     */
    public function getCommand()
    {
        $config = $this->config;
        $projectPath = getcwd();

        $pathWithParam = $config->server['path'] != ''
                       ? "-t $path"
                       : '';

        $command = [];
        $command[] = $config->options['phpBin'];
        $command[] = "-S {$this->getServerHost()}";
        $command[] = "-t {$projectPath}";
        $command[] = $this->getCommandParams();
        $command[] = "{$pathWithParam} {$config->server['file']}";

        return $command;
        return implode(' ', $command);
    }

    public function getCommandParams()
    {
        $params = [
            'remote_enable' => 1,
            'remote_mode' => 'req',
            'remote_host' => $this->config->debugger['host'],
            'remote_port' => $this->config->debugger['port'],
            'remote_connect_back' => 0,
        ];

        $commandParams = [];
        foreach($params as $key => $param) {
            $commandParams[] = '-dxdebug.'.$key.'='.$param;
        }

        return implode(' ', $commandParams);
    }

    public function run()
    {
        $command = $this->getCommand();
        $this->output->write(splashScreen());
        $this->output->writeln("Running command: <fg=red>{$command}</>\n");

        $this->output->writeln("Access in <comment>{$this->getServerHost()}</comment>\n");

        echo 'PROCESS';
        echo __DIR__ . '/process.pid';

        $builder = new ProcessBuilder($command);
        $builder->setWorkingDirectory(__DIR__ . '/process.pid');
        $builder->setTimeout(null);

        // shell_exec($command);
    }

    public function runAsDaemon()
    {
        $command = $this->getCommand();
        $this->output->write(splashScreen());
        $this->output->writeln("Running command: <fg=red>{$command}</>\n");

        $this->output->writeln("Access in <comment>{$this->getServerHost()}</comment>\n");

        //shell_exec($command);
    }
}
