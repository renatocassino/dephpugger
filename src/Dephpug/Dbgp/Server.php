<?php

namespace Dephpug\Dbgp;

use Dephpug\Output;
use Dephpug\MessageParse;
use Dephpug\Exception\ExitProgram;

/**
 * Class to create a socket to remote debugger in xDebug.
 *
 * Class contains the logic to make a connection with xDebug
 * and create a client socket to receive a code and send to
 * DBGP protocol
 */
class Server
{
    /**
     * Socket to connect xDebug.
     */
    private static $socket;

    /**
     * Socket server to debug.
     */
    private static $fdSocket;

    /**
     * Starts a client. Set socket server to start client and close the server.
     */
    public function startClient($host = 'localhost', $port = 9005)
    {
        self::$socket = socket_create(AF_INET, SOCK_STREAM, 0);
        @socket_set_option(self::$socket, SOL_SOCKET, SO_REUSEADDR, 1);
        @socket_bind(self::$socket, $host, $port);
        $result = socket_listen(self::$socket);
        assert($result);

        Output::print("<fg=blue> --- Listening on port {$port} ---</>\n");
        $this->eventConnectXdebugServer();
        socket_close(self::$socket);
    }

    /**
     * Close the client socket.
     */
    public function closeClient()
    {
        socket_close(self::$fdSocket);
    }

    /**
     * Remote commands are async. Method to wait xDebug response.
     */
    public function eventConnectXdebugServer()
    {
        self::$fdSocket = null;
        while (true) {
            self::$fdSocket = socket_accept(self::$socket);
            if (self::$fdSocket !== false) {
                Output::print('Connected to <fg=yellow;options=bold>XDebug server</>!');
                return true;
            }
        }
    }

    /**
     * Sends a command to the xdebug server.
     * Exits process on failure.
     *
     * @param string $command Command to send to DBGP
     */
    public function sendCommand($command)
    {
        $result = @socket_write(self::$fdSocket, "$command\0");
        if ($result === false) {
            $errorSocket = socket_last_error(self::$fdSocket);

            $error = 'Client socket error: '.socket_strerror($errorSocket);
            throw new ExitProgram($error, 1);
        }
        return true;
    }

    /**
     * Wait the response and set in static property.
     */
    public function getResponse()
    {
        $bytes = 0;
        $message = '';

        do {
            $buffer = '';
            $result = @socket_recv(self::$fdSocket, $buffer, 1024, 0);
            if ($result === false) {
                throw new ExitProgram('Client socket error', 1);
            }

            $bytes += $result;
            $message .= $buffer;
        } while ($message !== '' && $message[$bytes - 1] !== "\0");
        $messageParse = new MessageParse();

        return $messageParse->formatMessage($message);
    }
}
