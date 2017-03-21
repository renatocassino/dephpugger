<?php

namespace Dephpug;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class DbgpServer
{
    private $log;
    private $config;
    private static $output;
    private $transactionId = 1;
    private $messageParse;
    private $exporter;

    private static $conn;
    private static $socket;
    private static $fdSocket;
    private static $currentResponse;

    public function __construct()
    {
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
                self::$output->writeln('Connected to <fg=yellow;options=bold>XDebug server</>!');
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
            self::$output->writeln("<fg=red;options=bold>Error code: [{$errors['code']}] - {$errors['message']}</>");
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

        self::$output->writeln($responseMessage);
    }

    public function printIfIsStream()
    {
        $responses = self::$currentResponse;
        // This is hacky, but it works in all cases and doesn't require parsing xml.
        $prefix = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<stream";
        $isStream = $this->commandAdapter->startsWith($responses, $prefix);

        // Echo back the response to the user if it isn't a stream.
        if (!$isStream) {
            try {
                $responseParsed = $this->messageParse->formatXmlString(self::$currentResponse);

                if ($this->config->debugger['verboseMode']) {
                    self::$output->writeln("<comment>{$responseParsed}</comment>\n");
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

            if ('quit' === $cmd['command']) {
                $message = 'Quitting debugger request and restart listening';
                self::$output->writeln("\n<info> -- $message -- </info>\n");

                return;
            } elseif ('list' === $cmd['command']) {
                $offset = $this->filePrinter->offset;
                $newLine = min($this->filePrinter->line + $offset, $this->filePrinter->numberOfLines() - 1);
                $this->filePrinter->line = $newLine;
                self::$output->writeln($this->filePrinter->showFile(false));
            } elseif ('help' === $cmd['command']) {
                self::$output->writeln(Dephpugger::help());
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
                $line = trim(readline('(dephpug) => '));
            }

            return $line;
        }

        return 'continue';
    }

    public function init($output)
    {
        declare(ticks=1); // declare for pcntl_signal
        assert(pcntl_signal(SIGINT, ['DbgpServer', 'handle_sigint']));

        self::$output = $output;

        // Starting a connection class
        $this->startClient();

        // Message
        self::$output->writeln("<fg=blue> --- Listening on port {$this->config->debugger['port']} ---</>\n");

        $this->eventConnectXdebugServer();
        socket_close(self::$socket);
    }

    public function start()
    {
        do {
            $this->getResponse();
            $this->printResponse();
            $this->printIfIsStream();

            // Received response saying we're stopping.
            if ($this->commandAdapter->isStatusStop(self::$currentResponse)) {
                self::$output->writeln("<comment>-- Request ended, restarting... --</comment>\n");

                return;
            }

            // Ask command to dev
            $command = $this->getCommandToSend();
            $this->sendCommand($command);
        } while (true);

        socket_close(self::$fdSocket);
    }

    public static function getResponseByCommand($command)
    {
        $dbgpServer = new self();
        $dbgpServer->sendCommand($command);
        $dbgpServer->getResponse();

        return self::$currentResponse;
    }
}
