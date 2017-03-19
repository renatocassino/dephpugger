<?php

namespace Dephpug;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class DbgpServer
{
    private $log;
    private $config;
    private $output;
    private $transactionId = 1;
    private $messageParse;
    private $exporter;

    public function __construct($output)
    {
        $this->output = $output;
        $this->config = Config::getInstance();
        $this->commandAdapter = new CommandAdapter();
        $this->log = new Logger('name');
        $this->log->pushHandler(new StreamHandler(__DIR__.'/../../dephpugger.log'));
        $this->filePrinter = new FilePrinter();
        $this->filePrinter->setOffset($this->config->debugger['lineOffset']);
        $this->messageParse = new MessageParse();
        $this->exporter = new Exporter\Exporter();
    }

    /**
     * Create standart class for connection.
     */
    public function newConnection()
    {
        $conn = new \stdClass();
        $conn->fd = null;
        $conn->sendBreak = false;
        $conn->expectResponses = 1;
        $conn->port = $this->config->debugger['port'];

        return $conn;
    }

    /**
     * Starts a client.  Returns the socket and port used.
     *
     * @return array
     */
    public function startClient($port)
    {
        $this->log->warning('start_client');
        $socket = socket_create(AF_INET, SOCK_STREAM, 0);
        @socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        @socket_bind($socket, $this->config->debugger['host'], $port);
        $result = socket_listen($socket);
        assert($result);

        return [$socket, $port];
    }

    /* Sends a command to the xdebug server.  Exits process on failure. */
    public function sendCommand($fdSocket, $cmd)
    {
        $this->log->warning('send_command');

        $result = @socket_write($fdSocket, "$cmd\0");
        if ($result === false) {
            $error = $this->formatSocketError($fdSocket, 'Client socket error');
            throw new \Dephpug\Exception\ExitProgram($error, 1);
        }
    }

    public function eventConnectXdebugServer($socket)
    {
        $fdSocket = null;
        while (true) {
            $fdSocket = @socket_accept($socket);
            if ($fdSocket !== false) {
                $this->output->writeln('Connected to <fg=yellow;options=bold>XDebug server</>!');
                break;
            }
        }

        return $fdSocket;
    }

    /* Returns true iff the given message is a stream. */
    public function isStream($msg)
    {
        // This is hacky, but it works in all cases and doesn't require parsing xml.
        $prefix = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<stream";

        return $this->commandAdapter->startsWith($msg, $prefix);
    }

    protected function formatSocketError($fdSocket, $prefix)
    {
        $error = socket_last_error($fdSocket);

        return $prefix.': '.socket_strerror($error);
    }

    public function waitMessage(&$socket)
    {
        $bytes = 0;
        $message = '';
        do {
            $buffer = '';
            $result = @socket_recv($socket, $buffer, 1024, 0);
            if ($result === false) {
                throw new Exception\ExitProgram('Client socket error', 1);
            }

            $bytes += $result;
            $message .= $buffer;
        } while ($message !== '' && $message[$bytes - 1] !== "\0");

        return $this->messageParse->formatMessage($message);
    }

    public function readResponse($socket)
    {
        $message = $this->waitMessage($socket);
        $fileAndLine = $this->messageParse->getFileAndLine($message);

        if ($this->messageParse->isErrorMessage($message, $errors)) {
            $this->output->writeln("<fg=red;options=bold>Error code: [{$errors['code']}] - {$errors['message']}</>");

            return $message;
        }

        if (null === $fileAndLine) {
            // if is a value
            $this->exporter->setXml($message);
            $responseMessage = $this->exporter->printByXml() ?? '';
        } else {
            // if is a file
            $this->filePrinter->setFilename($fileAndLine[0]);
            $responseMessage = $this->filePrinter->showFile($fileAndLine[1]);
        }

        $this->output->writeln($responseMessage);

        if ($this->commandAdapter->startsWith($message, 'Client socket error')) {
            throw new Exception\ExitProgram('Client socket error', 1);
        }

        return $message;
    }

    public function readLine($response)
    {
        if (!preg_match('/\<init xmlns/', $response)) {
            $line = '';
            while ($line === '') {
                $line = trim(readline('(dephpug) => '));
            }

            return $line;
        }

        return 'continue';
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

        while (true) {
            // Wait for the expect number of responses. Normally we expect 1
            // response, but with the break command, we expect 2
            $responses = '';

            while ($conn->expectResponses > 0) {
                // Add Exception here
                // Return xml
                $response = $dbgpServer->readResponse($fdSocket);

                // Init packet doesn't end in </response>.
                $conn->expectResponses -= substr_count($response, '</response>');
                $conn->expectResponses -= substr_count($response, '</init>');
                $responses .= $response;
            }

            $conn->expectResponses = 1;

            // Might have been sent a Ctrl-c while waiting for the response.
            if ($conn->sendBreak) {
                $dbgpServer->sendCommand($fdSocket, "dbgp(break -i SIGINT\0)");
                $conn->sendBreak = false;
                // We're expecting a response for the break command, and the command
                // before the break command.
                $conn->expectResponses = 2;
                continue;
            }

            // Echo back the response to the user if it isn't a stream.
            if (!$dbgpServer->isStream($responses)) {
                $config = Config::getInstance();
                if ($config->options['verboseMode']) {
                    try {
                        $output->writeln("<comment>{$responses}</comment>\n");
                    } catch (\Symfony\Component\Console\Exception\InvalidArgumentException $e) {
                        echo "\n\n{$response}\n\n";
                    }
                }
            }

            // Received response saying we're stopping.
            if ($dbgpServer->commandAdapter->isStatusStop($responses)) {
                $output->writeln("<comment>-- Request ended, restarting... --</comment>\n");

                return;
            }

            // Get a command from the user and send it.
            $line = $dbgpServer->readLine($response);
            $cmd = CommandAdapter::convertCommand($line, $dbgpServer->transactionId++);
            $dbgpServer->sendCommand($fdSocket, $cmd);
        }
        socket_close($fdSocket);
        $conn->fd = null;
    }
}
