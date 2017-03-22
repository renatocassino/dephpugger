<?php

namespace Dephpug\Console\concerns;

class Printer
{
    public function requiredMessage($success, $message)
    {
        $emoji = $this->getEmoji($success);

        return "  {$emoji} {$message}";
    }

    public function getEmoji($success)
    {
        return ($success)
            ? '<fg=green;options=bold>✔</>️'
            : '<fg=red;options=bold>✖️</>';
    }
}
