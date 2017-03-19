<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;

class UnknownExporter implements iExporter
{
    public static function getType()
    {
        return 'unknown';
    }

    public function getExportedVar($xml)
    {
        return (string) $xml->property;
    }
}
