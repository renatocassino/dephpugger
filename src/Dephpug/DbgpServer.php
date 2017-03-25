<?php

namespace Dephpug;

class DbgpServer
{
    private $config;
    private $transactionId = 1;
    private $messageParse;
    private $exporter;

    private static $socket;
    private static $fdSocket;
    private static $currentResponse;

    public function __construct()
    {
        $this->config = Config::getInstance();
        $this->commandAdapter = new CommandAdapter();
        $this->filePrinter = new FilePrinter();
        $this->filePrinter->setOffset($this->config->debugger['lineOffset']);
        $this->messageParse = new MessageParse();
        $this->exporter = new Exporter\Exporter();
    }

    /**
     * Starts a client.  Returns the socket and port used.
     *
     * @return array
     */
    public function startClient()
    {
        self::$socket = socket_create(AF_INET, SOCK_STREAM, 0);
        @socket_set_option(self::$socket, SOL_SOCKET, SO_REUSEADDR, 1);
        @socket_bind(self::$socket, $this->config->debugger['host'], $this->config->debugger['port']);
        $result = socket_listen(self::$socket);
        assert($result);

        $this->eventConnectXdebugServer();
        socket_close(self::$socket);
    }

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
            self::$fdSocket = @socket_accept(self::$socket);
            if (self::$fdSocket !== false) {
                Output::print('Connected to <fg=yellow;options=bold>XDebug server</>!');
                break;
            }
        }
    }

    /**
     * Sends a command to the xdebug server.
     * Exits process on failure.
     */
    public function sendCommand($command)
    {
        $result = @socket_write(self::$fdSocket, "$command\0");
        if ($result === false) {
            $errorSocket = socket_last_error(self::$fdSocket);

            $error = $prefix.'Client socket error: '.socket_strerror($errorSocket);
            throw new \Dephpug\Exception\ExitProgram($error, 1);
        }
    }

    /**
     * Commands to xDebug are async. While true to get message.
     *
     * @return string xml format
     */
    public function getResponse()
    {
        $bytes = 0;
        $message = '';
        do {
            $buffer = '';
            $result = @socket_recv(self::$fdSocket, $buffer, 1024, 0);
            if ($result === false) {
                throw new Exception\ExitProgram('Client socket error', 1);
            }

            $bytes += $result;
            $message .= $buffer;
        } while ($message !== '' && $message[$bytes - 1] !== "\0");

        self::$currentResponse = $this->messageParse->formatMessage($message);
    }

    /**
     * After send command, get the response.
     */
    public function printResponse()
    {
        $fileAndLine = $this->messageParse->getFileAndLine(self::$currentResponse);

        if ($this->messageParse->isErrorMessage(self::$currentResponse, $errors)) {
            Output::print("<fg=red;options=bold>Error code: [{$errors['code']}] - {$errors['message']}</>");
        }

        if (null === $fileAndLine) {
            // if is a value
            $this->exporter->setXml(self::$currentResponse);
            $responseMessage = "<comment>{$this->exporter->printByXml()}</comment>" ?? '';
        } else {
            // if is a file
            $this->filePrinter->setFilename($fileAndLine[0]);
            $this->filePrinter->line = $fileAndLine[1];
            $responseMessage = $this->filePrinter->showFile();
        }

        Output::print($responseMessage);
    }

    public function printIfIsStream()
    {
        $responses = self::$currentResponse;
        // This is hacky, but it works in all cases and doesn't require parsing xml.
        $prefix = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<stream";
        $isStream = $this->messageParse->startsWith($responses, $prefix);

        // Echo back the response to the user if it isn't a stream.
        if (!$isStream) {
            try {
                $responseParsed = $this->messageParse->xmlBeautifier(self::$currentResponse);

                if ($this->config->debugger['verboseMode']) {
                    Output::print("<comment>{$responseParsed}</comment>\n");
                }
            } catch (\Symfony\Component\Console\Exception\InvalidArgumentException $e) {
                $currentResponse = self::$currentResponse;
                echo "\n\n{$currentResponse}\n\n";
            }
        }
    }

    /**
     * Command to run local or remote commands.
     */
    public function getCommandToSend()
    {
        while (true) {
            // Get a command from the user and send it.
            $line = $this->readLine();
            $command = CommandAdapter::convertCommand($line, $this->transactionId++);

            // Refactor this part bellow
            if (!is_array($command)) {
                return $command;
            }

            if ('quit' === $command['command']) {
                $message = 'Quitting debugger request and restart listening';
                Output::print("\n<info> -- $message -- </info>\n");
                throw new Exception\QuitException('');
            } elseif ('list' === $command['command']) {
                $offset = $this->filePrinter->offset;
                $newLine = min($this->filePrinter->line + $offset, $this->filePrinter->numberOfLines() - 1);
                $this->filePrinter->line = $newLine;
                Output::print($this->filePrinter->showFile(false));
            } elseif ('list-previous' === $command['command']) {
                $offset = $this->filePrinter->offset;
                $newLine = max($this->filePrinter->line - $offset, 0);
                $this->filePrinter->line = $newLine;
                Output::print($this->filePrinter->showFile(false));
            } elseif ('help' === $command['command']) {
                Output::print(Dephpugger::help());
            }
        }
    }

    /**
     * Command to read line (like scanf in C).
     *
     * @return string
     */
    public function readLine()
    {
        if (!preg_match('/\<init xmlns/', self::$currentResponse)) {
            $line = '';
            while ($line === '') {
                $line = trim(Readline::readline());
            }

            return $line;
        }

        return 'continue';
    }

    public function getCurrentResponse()
    {
        return self::$currentResponse;
    }

    public function getResponseByCommand($command)
    {
        $this->sendCommand($command);
        $this->getResponse();

        return self::$currentResponse;
    }
}
