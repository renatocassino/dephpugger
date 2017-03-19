<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;

class FloatExporter implements iExporter
{
    public static function getType()
    {
        return 'float';
    }

    public function getExportedVar($xml)
    {
        return (string) $xml->property;
    }
}
