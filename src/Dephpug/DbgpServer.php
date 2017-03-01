<?php

namespace Dephpug;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

use Exception\ExitProgram;

class DbgpServer
{
    private $log;
    private $config;
    private $output;

    public function __construct($output)
    {
        $this->output = $output;
        $this->config = Config::getInstance();
        $this->commandAdapter = new CommandAdapter();
        $this->log = new Logger('name');
        $this->log->pushHandler(new StreamHandler(__DIR__ . '/../../dephpugger.log'));
        $this->filePrinter = new FilePrinter();
    }

    /**
     * Create standart class for connection
     */
    public function newConnection()
    {
        $conn = new \stdClass;
        $conn->fd = null;
        $conn->sendBreak = false;
        $conn->expectResponses = 1;
        $conn->port = $this->config->debugger['port'];
        return $conn;
    }

    /**
     * Starts a client.  Returns the socket and port used.
     * @return array
     */
    public function startClient($port) {
        $this->log->warning('start_client');
        $socket = socket_create(AF_INET, SOCK_STREAM, 0);
        @socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        @socket_bind($socket, $this->config->debugger['host'], $port);
        $result = socket_listen($socket);
        assert($result);
        return [$socket, $port];
    }

    /* Sends a command to the xdebug server.  Exits process on failure. */
    function sendCommand($fdSocket, $cmd) {
        $this->log->warning('send_command');

        list($valid, $command) = CommandAdapter::convertCommand($cmd, 1);
        if($valid) {
            $cmd = $command;
        }

        $result = @socket_write($fdSocket, "$cmd\0");
        if ($result === false) {
            $error = $this->formatSocketError($fdSocket, "Client socket error");
            throw new ExitProgram($error, 1);
        }
    }

    public function eventConnectXdebugServer($socket)
    {
        $fdSocket = null;
        while (true) {
            $fdSocket = @socket_accept($socket);
            if ($fdSocket !== false) {
                $this->output->writeln("Connected to an <fg=yellow;options=bold>XDebug server</>!");
                break;
            }
        }
        return $fdSocket;
    }
    
    /* Returns true iff the given message is a stream. */
    public function isStream($msg) {
        // This is hacky, but it works in all cases and doesn't require parsing xml.
        $prefix = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<stream";
        return $this->commandAdapter->startsWith($msg, $prefix);
    }

    /* Formats the given dbgp response for output. */
    public function formatResponse($message) {
        // Remove # of bytes + null characters.
        $message = str_replace("\0", "", $message);
        $message = preg_replace("/^[0-9]+?(?=<)/", "", $message);
        // Remove strings that could change between runs.
        $message = preg_replace('/appid="[0-9]+"/', 'appid=""', $message);
        $message = preg_replace('/engine version=".*?"/', 'engine version=""', $message);
        $message = preg_replace('/protocol_version=".*?"/', 'protocol_version=""', $message);
        $message = preg_replace('/ idekey=".*?"/', '', $message);
        $message = preg_replace('/address="[0-9]+"/', 'address=""', $message);
        if($message !== '') {
            $this->log->warning('Message format: ' . $message);
        }
        return $message;
    }

    protected function formatSocketError($fdSocket, $prefix) {
        $error = socket_last_error($fdSocket);
        return $prefix . ": " . socket_strerror($error);
    }

    public function readResponse($socket) {
        $bytes = 0;
        $message = "";
        do {
            $buffer = "";
            $result = @socket_recv($socket, $buffer, 1024, 0);
            if ($result === false) {
                return $this->formatSocketError($socket, "Client socket error") . "\n";
            }
            $bytes += $result;
            $message .= $buffer;
        } while ($message !== "" && $message[$bytes - 1] !== "\0");

        $this->filePrinter->printFileByMessage($message);
        $formatResponse = $this->formatResponse($message);

        /*
          if($dbgpServer->commandAdapter->startsWith($response, "Client socket error")) {
              throw new Dephpug\Exception\SocketException();
          }
         */
        return $formatResponse;
    }

    public static function start($output)
    {
        declare(ticks=1); // declare for pcntl_signal
        assert(pcntl_signal(SIGINT, ['DbgpServer', 'handle_sigint']));

        // Starting dbgpServer
        $dbgpServer = new static($output);

        // Starting a connection class
        $conn = $dbgpServer->newConnection();
        list($socket, $port) = $dbgpServer->startClient($conn->port);

        // Message
        $output->writeln("<fg=blue> --- Listening on port $port ---</>\n");

        // Getting XDebug Connection
        $fdSocket = $dbgpServer->eventConnectXdebugServer($socket);
        socket_close($socket);

        $conn->fd = $fdSocket;

        while(true) {
            // Wait for the expect number of responses. Normally we expect 1
            // response, but with the break command, we expect 2
            $responses = '';

            while($conn->expectResponses > 0) {
                // Add Exception here
                // Return xml
                $response = $dbgpServer->readResponse($fdSocket);

                // !TODO - Change this if for an exception in readResponse
                if ($dbgpServer->commandAdapter->startsWith($response, "Client socket error")) {
                    break;
                }

                // Init packet doesn't end in </response>.
                $conn->expectResponses -= substr_count($response, "</response>");
                $conn->expectResponses -= substr_count($response, "</init>");
                $responses .= $response;
            }

            $conn->expectResponses = 1;

            // Might have been sent a Ctrl-c while waiting for the response.
            if ($conn->sendBreak) {
                $dbgpServer->sendCommand($fdSocket, "break -i SIGINT\0");
                $conn->sendBreak = false;
                // We're expecting a response for the break command, and the command
                // before the break command.
                $conn->expectResponses = 2;
                continue;
            }

            // Echo back the response to the user if it isn't a stream.
            if (!$dbgpServer->isStream($responses)) {
                $config = Config::getInstance();
                if($config->options['verboseMode']) {
                    $output->writeln("<comment>$responses\n</comment>");
                }
            }

            // Received response saying we're stopping.
            if ($dbgpServer->commandAdapter->isStatusStop($responses)) {
                $output->writeln("<comment>-- Request ended, restarting... --</comment>\n");
                return;
            }

            // Get a command from the user and send it.
            $line = trim(readline("(dephpug) $ "));
            if ($line === "") {
                continue;
            }

            $dbgpServer->sendCommand($fdSocket, $line);
        }
        socket_close($fdSocket);
        $conn->fd = null;
    }
}
