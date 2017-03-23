<?php

namespace Dephpug;

class CommandAdapter
{
    public static function convertCommand($command, $transactionId)
    {
        return self::convertCommandToDBGp($command, $transactionId);
    }

    public function startsWith($big, $small)
    {
        $slen = strlen($small);

        return $slen === 0 || strncmp($big, $small, $slen) === 0;
    }

    public function isStatusStop($responses)
    {
        return preg_match('/status=\"stopp(?:ed|ing)\"/', $responses);
    }

    public static function convertCommandToDBGp($command, $transactionId)
    {
        $config = Config::getInstance();
        // Example format: $variable = 33;
        if (preg_match('/^\$([\w_\[\]\"\\\'\-\>\{\}]+)(?: )*=(?: )*([\'\"\w\.]+)\;?$/', $command, $result)) {
            $variableName = $result[1];
            $value = base64_encode($result[2]);
            $command = "property_set -i {$transactionId} -n \${$variableName} -- {$value}";
            if ($config->debugger['verboseMode']) {
                echo $command.PHP_EOL;
            }

            return $command;
        }

        if (preg_match('/^dbgp\(([^;]+)\);?/', $command, $result)) {
            $command = $result[1];
            if ($config->debugger['verboseMode']) {
                echo $command.PHP_EOL;
            }

            return $command;
        }

        // Example format: $variable
        if (preg_match('/^\$([\w_\[\]\"\\\'\-\>\{\}]+);?$/', $command, $result)) {
            $variableName = $result[1];
            $typeVarName = uniqid();
            $contentVarName = uniqid();
            $command = "property_get -i {$transactionId} -n {$variableName}";
            //$commandEncoded = base64_encode($command);
            //$command = "eval -i 1 -- {$commandEncoded}";

            if ($config->debugger['verboseMode']) {
                echo $command.PHP_EOL;
            }

            return $command;
        }

        // Simple commands
        switch ($command) {
            case 'n':
            case 'next': $newCommand = "step_over -i {$transactionId}"; break;
            case 's':
            case 'step': $newCommand = "step_into -i {$transactionId}"; break;
            case 'c':
            case 'continue': $newCommand = "run -i {$transactionId}"; break;
            case 'l':
            case 'list': return ['command' => 'list'];
            case 'lp':
            case 'list-previous': return ['command' => 'list-previous'];
            case 'h':
            case 'help': return ['command' => 'help'];
            case 'q':
            case 'quit': return ['command' => 'quit'];
            default: $newCommand = "eval -i {$transactionId} -- ".base64_encode($command);
        }

        if ($config->debugger['verboseMode']) {
            echo $newCommand.PHP_EOL;
        }

        return $newCommand;
    }
}
