<?php

namespace Dephpug;

class CommandAdapter {
    public static function convertCommand($command, $transactionId) {
        return self::convertCommandToDBGp($command, $transactionId);
    }

    public static function convertCommandToDBGp($command, $transactionId) {
        // Example format: $variable
        if(preg_match('/^\$([\w_]+);?$/', $command, $result)) {
            $variableName = $result[1];
            return [true, "property_get -i {$transactionId} -n {$variableName}"];
        }

        // Example format: $variable = 33;
        if(preg_match('/^\$([\w_]+)(?: )*=(?: )*([\'\"\w\.]+)\;?$/', $command, $result)) {
            $variableName = $result[1];
            $value = $result[2];
            return [true, "property_set -i {$transactionId} -n {$variableName} -- {$value}"];
        }

        if(preg_match('/^dbgp\((.*)\);?/', $command, $result)) {
            $command = $result[1];
            return [false, $command];
        }

        $valid = true;
        // Simple commands
        switch($command) {
            case 'n': $newCommand = "step_over -i {$transactionId}"; break;
            case 's': $newCommand = "step_into -i {$transactionId}"; break;
            case 'c': $newCommand = "run -i {$transactionId}"; break;
            default: $newCommand = "eval -i {$transactionId} -- " . base64_encode($command);
        }
        return [$valid, $newCommand];
    }
}
