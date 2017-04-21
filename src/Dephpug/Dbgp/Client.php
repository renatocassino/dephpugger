<?php

namespace Dephpug\Dbgp;

/**
 * Class to use Dbgp\Server with methods to convert in commands to dbgp.
 *
 * Class is a better interface to send commands to dbgp
 * protocol. You can use ->run instead of run -i 1, for example.
 * Obs: When you send a command, you need to get the result in
 * ->getResponse() method.
 */
class Client
{
    /**
     * Transaction needed to use in dbgp protocol.
     */
    private $transactionId = 0;

    /**
     * Object to make connection with dbgp.
     */
    public $dbgpServer;

    /**
     * If has message to receive.
     */
    protected $hasMessage = true;

    public function __construct()
    {
        $this->dbgpServer = new Server();
    }

    /**
     * Start dbgpServer client.
     *
     * @param string $host
     * @param int    $port
     */
    public function startClient($host, $port)
    {
        $this->dbgpServer->startClient($host, $port);
    }

    /**
     * Check if has a new received message.
     *
     * @return bool $hasMessage
     */
    public function hasMessage()
    {
        return $this->hasMessage;
    }

    /**
     * Method to send native commands to dbgp protocol.
     */
    public function run($command)
    {
        $this->hasMessage = true;
        $command = str_replace('{id}', $this->transactionId++, $command);

        $this->dbgpServer->sendCommand($command);
    }

    /**
     * Get always a new number for a transaction.
     * Auto increment.
     *
     * @return int $transactionId
     */
    public function getTransactionId()
    {
        return $this->transactionId++;
    }

    /**
     * Command step_into to dbgp server.
     *
     * @example step_into -i 1
     */
    public function stepInto()
    {
        $this->run('step_into -i {id}');
    }

    /**
     * Command to step over to next line if exists.
     *
     * @example step_over -i 1
     */
    public function next()
    {
        $this->run('step_over -i {id}');
    }

    /**
     * Command to send run to debugger. Will stop only if exists
     * another breakpoint.
     *
     * @example run -i 1
     */
    public function continue()
    {
        $this->run('run -i {id}');
    }

    /**
     * Command to send a php code to dbgp server.
     * All commands must be in base64, but the parameter doesnt need.
     *
     * @param string $command
     */
    public function eval($command)
    {
        $commandEncoded = base64_encode($command);
        $this->run("eval -i {id} -- $commandEncoded");
    }

    /**
     * Command to get a variable (property in dbgp).
     *
     * @example property_get -i 1 -n $myVariable
     */
    public function propertyGet($variable)
    {
        $this->run("property_get -i {id} -n $variable");
    }

    /**
     * Set a value to a variable.
     *
     * @example property_set -i 1 -n $myVariable -- MTIz
     */
    public function propertySet($varname, $value)
    {
        $value = base64_encode($value);
        $this->run("property_set -i {id} -n \${$varname} -- {$value}");
    }

    /**
     * Get response after a command has been sent.
     *
     * @return string|null
     */
    public function getResponse()
    {
        if (!$this->hasMessage) {
            return null;
        }
        $this->hasMessage = false;

        return $this->dbgpServer->getResponse();
    }
}
