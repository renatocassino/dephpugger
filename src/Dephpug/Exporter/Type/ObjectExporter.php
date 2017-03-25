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
        $responseXDebug = $this->getResponseByCommand($command);
        $newXml = simplexml_load_string($responseXDebug);
        $content = base64_decode((string) $newXml->property);

        return $content;
    }

    public function getResponseByCommand($command)
    {
        $dbgpServer = new DbgpServer();
        $transactionId = $dbgpServer->getTransactionId();
        $dbgpServer->sendCommand('eval -i {$transactionId} -- '.$command);

        return $dbgpServer->getCurrentResponse();
    }
}
