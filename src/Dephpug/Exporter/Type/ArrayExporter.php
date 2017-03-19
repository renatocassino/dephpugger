<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;

class ArrayExporter implements iExporter
{
    public static function getType()
    {
        return 'array';
    }

    public function getExportedVar($xml)
    {
        $data = $this->getArrayFormat($xml->property);

        return PHP_EOL.json_encode($data, JSON_PRETTY_PRINT);
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
