<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;
use Dephpug\DbgpServer;

class ArrayExporter implements iExporter
{
    public static function getType()
    {
        return 'array';
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

    private function getArrayFormat($elements)
    {
        $data = [];
        foreach ($elements->children() as $child) {
            $key = (string) $child->attributes()['name'];

            switch ($child->attributes()['type']) {
            case 'int':
            case 'float':
                $data[$key] = $child->__toString(); break;
            case 'string':
                $data[$key] = base64_decode($child->__toString()); break;
            case 'array':
                $data[$key] = '(array) [...]';
            }
        }

        return $data;
    }
}
