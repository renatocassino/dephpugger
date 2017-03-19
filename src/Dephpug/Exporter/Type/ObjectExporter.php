<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;

class ObjectExporter implements iExporter
{
    public static function getType()
    {
        return 'object';
    }

    public function getExportedVar($xml)
    {
        $obj = $this->getObjectFormat($xml->property);

        return json_encode($obj, JSON_PRETTY_PRINT);
    }

    public function getObjectFormat($elements)
    {
        $content = [];
        foreach ($elements->children() as $el) {
            $type = 'null' === (string) $el['type'] ? 'method' : $el['type'];
            $value = 'base64' === (string) $el['encoding']
                   ? base64_decode((string) $el)
                   : (string) $el;
            if ('' !== $value) {
                $value = ' => '.$value;
            }

            $currentContent = "({$type}) `{$el['facet']}`{$value}";

            $key = (string) $el['name'];
            if ('method' === $type) {
                $key .= '()';
            }
            $content[$key] = $currentContent;
        }

        return $content;
    }
}
