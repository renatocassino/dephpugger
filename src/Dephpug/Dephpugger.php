<?php

namespace Dephpug;

use Symfony\Component\Console\Application;

/**
 * Class indicates info about the project
 */
class Dephpugger
{
    /** The version of the Dephpugger */
    public static $VERSION = '1.1.2';

    protected $commands = [
        \Dephpug\Console\CliCommand::class,
        \Dephpug\Console\DebuggerCommand::class,
        \Dephpug\Console\InfoCommand::class,
        \Dephpug\Console\RequirementsCommand::class,
        \Dephpug\Console\ServerCommand::class,
    ];

    public function run()
    {
        $application = new Application();

        foreach ($this->commands as $command) {
            $application->add(new $command);
        }

        return $application->run();
    }
}
