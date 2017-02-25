<?php

namespace Dephpug;

class CommandAdapter {
    public static function convertCommand($command, $transactionId) {
        return self::convertCommandToDBGp($command, $transactionId);
    }

    public static function convertCommandToDBGp($command, $transactionId) {
        $valid = true;
        switch($command) {
        case 'n': $newCommand = "step_over -i {$transactionId}"; break;
        case 's': $newCommand = "step_into -i {$transactionId}"; break;
        default: $newCommand = $command; $valid = false;
        }
        return [$valid, $newCommand];
    }
}
