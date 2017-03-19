<?php

namespace Dephpug\Exception;

class SocketException extends \Exception
{
    private $statusMessage = [
        0 => 'Unexpected error',
        1 => 'Client socket error',
        99 => null,
    ];

    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        $statusMessageError = $this->getStatusMessage();
        if ($statusMessageError !== '') {
            $statusMessageError .= ' - ';
        }

        return __CLASS__.": [{$this->code}]: {$statusMessageError}{$this->message}";
    }
}
