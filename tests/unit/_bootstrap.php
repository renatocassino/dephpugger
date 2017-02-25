<?php
// Here you can initialize variables that will be available to your tests
require 'vendor/autoload.php';

foreach(glob('src/Dephpug/*.php') as $file)
    require $file;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;

$output = new ConsoleOutput();
$output->setFormatter(new OutputFormatter(true));

$output->writeln(
    'The <comment>tests</comment> is <bg=magenta;fg=cyan;option=blink>running!</>'
);
