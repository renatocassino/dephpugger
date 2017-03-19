<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;

class NullExporter implements iExporter
{
    public static function getType()
    {
        return 'null';
    }

    public function getExportedVar($xml)
    {
        return 'null';
    }
}
