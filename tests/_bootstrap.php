<?php
// Here you can initialize variables that will be available to your tests
require 'vendor/autoload.php';

$paths = array_merge(
    glob('src/Dephpug/*.php'),
    glob('src/Dephpug/*/*.php'),
    glob('src/Dephpug/*/*/*.php')
);

foreach ($paths as $file) {
    include_once $file;
}

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;

$output = new ConsoleOutput();
$output->setFormatter(new OutputFormatter(true));

$output->writeln(
    'The <comment>tests</comment> are <fg=cyan;>running!</>'
);
