<?php

$pharFile = 'dephpugger.phar';

if (file_exists($pharFile)) {
    unlink($pharFile);
}

if (file_exists($pharFile . '.gz')) {
    unlink($pharFile . '.gz');
}

$phar = new Phar($pharFile, 0, $pharFile);
$phar->setSignatureAlgorithm(Phar::SHA1);
$phar->buildFromDirectory('.', "/(bin|src|vendor).*(?:\.php|dephpugger)$/i");
$phar->setDefaultStub('./bin/dephpugger');
$phar->compress(Phar::GZ);

$phar->setStub("#!/usr/bin/env php\n<?php Phar::mapPhar('dephpugger.phar'); require 'phar://dephpugger.phar/bin/dephpugger'; __HALT_COMPILER();");

echo "File $pharFile created!";
