<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;

class ResourceExporter implements iExporter
{
    public static function getType()
    {
        return 'resource';
    }

    public function getExportedVar($xml)
    {
        return (string) '['.$xml->property.']';
    }
}
