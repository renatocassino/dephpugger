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
        $this->setConnectionClass();
    }

    public function formatXmlString($xml){
        $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
        $token      = strtok($xml, "\n");
        $result     = '';
        $pad        = 0; 
        $matches    = array();
        while ($token !== false) : 
            if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) : 
                $indent=0;
        elseif (preg_match('/^<\/\w/', $token, $matches)) :
            $pad--;
        $indent = 0;
        elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
            $indent=1;
        else :
            $indent = 0; 
        endif;
        $line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
        $result .= $line . "\n";
        $token   = strtok("\n");
        $pad    += $indent;
        endwhile; 
        return $result;
    }


    /**
     * Create standart class for connection.
     */
    public function setConnectionClass()
    {
        self::$conn = new \stdClass();
        self::$conn->port = $this->config->debugger['port'];
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
     * Remote commands are async. Method to wait xDebug response
     *
     * @return void
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
     * @return string xml format
     */
    public function waitMessage()
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

        return $this->messageParse->formatMessage($message);
    }

    /**
     * 
     */
    public function readResponse()
    {
        $message = $this->waitMessage();
        $fileAndLine = $this->messageParse->getFileAndLine($message);

        if ($this->messageParse->isErrorMessage($message, $errors)) {
            self::$output->writeln("<fg=red;options=bold>Error code: [{$errors['code']}] - {$errors['message']}</>");

            return $message;
        }

        if (null === $fileAndLine) {
            // if is a value
            $this->exporter->setXml($message);
            $responseMessage = "<comment>{$this->exporter->printByXml()}</comment>" ?? '';
        } else {
            // if is a file
            $this->filePrinter->setFilename($fileAndLine[0]);
            $this->filePrinter->line = $fileAndLine[1];
            $responseMessage = $this->filePrinter->showFile();
        }

        self::$output->writeln($responseMessage);

        if ($this->commandAdapter->startsWith($message, 'Client socket error')) {
            throw new Exception\ExitProgram('Client socket error', 1);
        }

        return $message;
    }

    /**
     * Wait for the expect number of responses. Normally we expect 1
     * response, but with the break command, we expect 2
     *
     * @return string xml format
     */
    public function getResponse()
    {
        $responses = '';
        $expectResponses = 1;

        while ($expectResponses > 0) {
            // Try get any response
            self::$currentResponse = $this->readResponse();

            // Init packet doesn't end in </response>.
            $expectResponses -= substr_count(self::$currentResponse, '</response>');
            $expectResponses -= substr_count(self::$currentResponse, '</init>');
            $responses .= self::$currentResponse;
        }

        $this->printIfIsStream($responses);

        return $responses;
    }

    public function printIfIsStream($responses)
    {
        // This is hacky, but it works in all cases and doesn't require parsing xml.
        $prefix = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n<stream";
        $isStream = $this->commandAdapter->startsWith($responses, $prefix);

        // Echo back the response to the user if it isn't a stream.
        if (!$isStream) {
            try {
                $responseParsed = $this->formatXmlString(self::$currentResponse);

                if($this->config->debugger['verboseMode']) {
                    self::$output->writeln("<comment>{$responseParsed}</comment>\n");
                }
            } catch (\Symfony\Component\Console\Exception\InvalidArgumentException $e) {
                $currentResponse = self::$currentResponse;
                echo ("\n\n{$currentResponse}\n\n");
            }
        }
    }

    /**
     * Command to run local or remote commands
     */
    public function getCommandToSend()
    {
        while(true) {
            // Get a command from the user and send it.
            $line = $this->readLine();
            $command = CommandAdapter::convertCommand($line, $this->transactionId++);

            // Refactor this part bellow
            if (!is_array($command)) {
                return $command;
            }

            if('quit' === $cmd['command']) {
                $message = 'Quitting debugger request and restart listening';
                self::$output->writeln("\n<info> -- $message -- </info>\n");
                return;
            } elseif('list' === $cmd['command']) {
                $offset = $this->filePrinter->offset;
                $newLine = min($this->filePrinter->line+$offset, $this->filePrinter->numberOfLines()-1);
                $this->filePrinter->line = $newLine;
                self::$output->writeln($this->filePrinter->showFile(false));
            } elseif('help' === $cmd['command']) {
                self::$output->writeln(Dephpugger::help());
            }
        }
    }

    /**
     * Command to read line (like scanf in C)
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
        $port = self::$conn->port;
        self::$output->writeln("<fg=blue> --- Listening on port {$port} ---</>\n");

        $this->eventConnectXdebugServer();
        socket_close(self::$socket);
    }

    public function start()
    {
        // Get first message response
        $this->getResponse();

        while (true) {
            // Ask command to dev
            $command = $this->getCommandToSend();
            $this->sendCommand($command);

            // Get response
            $responses = $this->getResponse();

            // Received response saying we're stopping.
            if ($this->commandAdapter->isStatusStop($responses)) {
                self::$output->writeln("<comment>-- Request ended, restarting... --</comment>\n");

                return;
            }
        }

        socket_close(self::$fdSocket);
    }

    public static function getResponseByCommand($command)
    {
        $dbgpServer = new DbgpServer();
        $dbgpServer->sendCommand($command);
        return $dbgpServer->getResponse();
    }
}
