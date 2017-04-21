<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;
use Dephpug\Dbgp\Client;

class ObjectExporter implements iExporter
{
    public static function getType()
    {
        return 'object';
    }

    public function getExportedVar($xml)
    {
        $command = "var_export({$xml->property->attributes()['name']}, true);";
        $responseXDebug = $this->getResponseByCommand($command);
        $newXml = simplexml_load_string($responseXDebug);
        $content = base64_decode((string) $newXml->property);

        return $content;
    }

    public function getResponseByCommand($command)
    {
        $dbgpClient = new Client();
        $dbgpClient->eval($command);

        return $dbgpClient->getResponse();
    }
}
