<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;

class BoolExporter implements iExporter
{
    public static function getType()
    {
        return 'bool';
    }

    public function getExportedVar($xml)
    {
        return (1 == $xml->property)
                  ? 'true'
                  : 'false';
    }
}
