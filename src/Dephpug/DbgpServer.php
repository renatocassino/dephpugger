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

    private $conn;
    private static $socket;
    private static $fdSocket;
    private static $currentResponse;

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
        $this->conn = new \stdClass();
        $this->conn->expectResponses = 1;
        $this->conn->port = $this->config->debugger['port'];
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

    public function eventConnectXdebugServer()
    {
        self::$fdSocket = null;
        while (true) {
            self::$fdSocket = @socket_accept(self::$socket);
            if (self::$fdSocket !== false) {
                $this->output->writeln('Connected to <fg=yellow;options=bold>XDebug server</>!');
                break;
            }
        }

        return self::$fdSocket;
    }

    /* Sends a command to the xdebug server.  Exits process on failure. */
    public function sendCommand($command)
    {
        $this->log->warning('send_command');

        $result = @socket_write(self::$fdSocket, "$command\0");
        if ($result === false) {
            $error = $this->formatSocketError('Client socket error');
            throw new \Dephpug\Exception\ExitProgram($error, 1);
        }
    }

    protected function formatSocketError($prefix)
    {
        $error = socket_last_error(self::$fdSocket);

        return $prefix.': '.socket_strerror($error);
    }

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

    public function readResponse()
    {
        $message = $this->waitMessage();
        $fileAndLine = $this->messageParse->getFileAndLine($message);

        if ($this->messageParse->isErrorMessage($message, $errors)) {
            $this->output->writeln("<fg=red;options=bold>Error code: [{$errors['code']}] - {$errors['message']}</>");

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

        $this->output->writeln($responseMessage);

        if ($this->commandAdapter->startsWith($message, 'Client socket error')) {
            throw new Exception\ExitProgram('Client socket error', 1);
        }

        return $message;
    }

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

    /**
     * Wait for the expect number of responses. Normally we expect 1
     * response, but with the break command, we expect 2
     */
    public function waitResponses()
    {
        $responses = '';
        while ($this->conn->expectResponses > 0) {
            self::$currentResponse = $this->readResponse();

            // Init packet doesn't end in </response>.
            $this->conn->expectResponses -= substr_count(self::$currentResponse, '</response>');
            $this->conn->expectResponses -= substr_count(self::$currentResponse, '</init>');
            $responses .= self::$currentResponse;
        }

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
                $r = $this->formatXmlString(self::$currentResponse);
                $this->output->writeln("<comment>{$r}</comment>\n");
            } catch (\Symfony\Component\Console\Exception\InvalidArgumentException $e) {
                echo "\n\n{self::$currentResponse}\n\n";
            }
        }
    }

    public function getCommandToSend()
    {
        while(true) {
            // Get a command from the user and send it.
            $line = $this->readLine();
            $command = CommandAdapter::convertCommand($line, $this->transactionId++);
            if (!is_array($command)) {
                return $command;
            }

            if('quit' === $cmd['command']) {
                $message = 'Quitting debugger request and restart listening';
                $this->output->writeln("\n<info> -- $message -- </info>\n");
                return;
            } elseif('list' === $cmd['command']) {
                $offset = $this->filePrinter->offset;
                $newLine = min($this->filePrinter->line+$offset, $this->filePrinter->numberOfLines()-1);
                $this->filePrinter->line = $newLine;
                $this->output->writeln($this->filePrinter->showFile(false));
            } elseif('help' === $cmd['command']) {
                $this->output->writeln(Dephpugger::help());
            }
        }
    }

    public function init()
    {
        declare(ticks=1); // declare for pcntl_signal
        assert(pcntl_signal(SIGINT, ['DbgpServer', 'handle_sigint']));

        // Starting a connection class
        $this->setConnectionClass();
        $this->startClient();

        // Message
        $this->output->writeln("<fg=blue> --- Listening on port {$this->conn->port} ---</>\n");
    }

    public function start()
    {
        // Getting XDebug Connection
        self::$fdSocket = $this->eventConnectXdebugServer();
        socket_close(self::$socket);

        while (true) {
            $responses = $this->waitResponses();

            $this->conn->expectResponses = 1;

            if($this->config->debugger['verboseMode']) {
                $this->printIfIsStream($responses);
            }

            // Received response saying we're stopping.
            if ($this->commandAdapter->isStatusStop($responses)) {
                $this->output->writeln("<comment>-- Request ended, restarting... --</comment>\n");

                return;
            }

            $command = $this->getCommandToSend();
            $this->sendCommand($command);
        }
        socket_close(self::$fdSocket);
    }
}
