<?php

namespace Dephpug\Exporter;

interface iExporter
{
    public static function getType();

    public function getExportedVar($xml);
}
