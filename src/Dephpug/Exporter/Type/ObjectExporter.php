<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;
use Dephpug\DbgpServer;

class ObjectExporter implements iExporter
{
    public static function getType()
    {
        return 'object';
    }

    public function getExportedVar($xml)
    {
        $command = "var_export({$xml->property->attributes()['name']}, true);";
        $command = base64_encode($command);
        $responseXDebug = DbgpServer::getResponseByCommand('eval -i 1 -- '.$command);
        $newXml = simplexml_load_string($responseXDebug);
        $content = base64_decode((string) $newXml->property);

        return $content;
    }
}
