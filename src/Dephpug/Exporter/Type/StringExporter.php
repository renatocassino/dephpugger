<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;

class StringExporter implements iExporter
{
    public static function getType()
    {
        return 'string';
    }

    public function getExportedVar($xml)
    {
        $content = base64_decode((string) $xml->property);

        return "{$content}";
    }
}
