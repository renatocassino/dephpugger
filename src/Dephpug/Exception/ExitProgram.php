<?php

namespace Dephpug\Exception;

class ExitProgram extends \Exception
{
    private $statusMessage = [
        0 => 'Unexpected error',
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

    public function getStatusMessage()
    {
        $statusMessageError = '';
        if (isset($this->statusMessage[$this->code])
            && $this->statusMessage[$this->code] != null
        ) {
            $statusMessageError = "{$this->statusMessage[$this->code]}";
        }

        return $statusMessageError;
    }
}
