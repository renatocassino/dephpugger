<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;

class IntegerExporter implements iExporter
{
    public static function getType()
    {
        return 'int';
    }

    public function getExportedVar($xml)
    {
        return (string) $xml->property;
    }
}
