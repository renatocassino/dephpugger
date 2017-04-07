<?php

namespace Dephpug;

class Dephpugger
{
    public static $VERSION = '0.5.1';
    public $filePrinter;

    public static function help()
    {
        return <<<'EOL'

<info>-- Help command --</info>

<options=bold>Commands to navigate:</>

<comment>`next`              </comment>--- Step out in code debugger
<comment>`n`                 </comment>--- Alias to next
<comment>`s`                 </comment>--- Get in method. Step into
<comment>`list`              </comment>--- Show next lines
<comment>`l`                 </comment>--- Alias to list next lines
<comment>`list-previous`     </comment>--- Show previous lines
<comment>`lp`                </comment>--- Alias to list previous lines
<comment>`continue`          </comment>--- Command to run code
<comment>`c`                 </comment>--- Alias to command
<comment>`dbgp(\<command>`)   </comment>--- Run native DBGP command
<comment>`quit`              </comment>--- Command to close the Dephpugger
<comment>`q`                 </comment>--- Alias to quit


<options=bold>Get variables</>

<fg=red>Obs: You don't need use echo or var_dump/print_r. Use only the variable or function to get the value exported.</>
<fg=green>`$variable`      </>- Get value of variable
<fg=green>`my_function()`  </>- Get value of my_function()
<fg=green>`$variable = 33` </>- Set $variable to 33

Ex: <comment>`str_repeat('a', 'b', 'blablabla')`</comment>
  => (string) blbblbblb

EOL;
    }

    public function start()
    {
        declare(ticks = 1);
        pcntl_signal(SIGTERM, "signal_handler");
        pcntl_signal(SIGINT, "signal_handler");

        $config = Config::getInstance();
        $this->filePrinter = new FilePrinter();
        $this->filePrinter->setOffset($config->debugger['lineOffset']);

        $dbgpServer = new DbgpServer();
        $dbgpServer->startClient($config->debugger['host'], $config->debugger['port']);
        $currentResponse = $dbgpServer->getResponse();
        $messageParse = new MessageParse();

        try {
            while (true) {
                // Ask command to dev
                $command = $this->getCommandToSend($currentResponse);
                $dbgpServer->sendCommand($command);
                $currentResponse = $dbgpServer->getResponse();

                $this->printResponse($currentResponse);
                if ($config->debugger['verboseMode']) {
                    $message = $messageParse->printIfIsStream($currentResponse);
                    if ($message) {
                        try {
                            Output::print($message);
                        } catch (\Exception $e) {
                            echo $message;
                        }
                    }
                }

                // Received response saying we're stopping.
                if ($messageParse->isStatusStop($currentResponse)) {
                    Output::print("<comment>-- Request ended, restarting... --</comment>\n");

                    return;
                }
            }
        } catch (Exception\QuitException $e) {
        }

        $dbgpServer->closeClient();
    }

    /**
     * After send command, get the response.
     */
    public function printResponse($currentResponse)
    {
        $messageParse = new MessageParse();
        $fileAndLine = $messageParse->getFileAndLine($currentResponse);
        $this->filePrinter = new FilePrinter();
        $exporter = new Exporter\Exporter();

        if ($messageParse->isErrorMessage($currentResponse, $errors)) {
            Output::print("<fg=red;options=bold>Error code: [{$errors['code']}] - {$errors['message']}</>");
        }

        if (null === $fileAndLine) {
            // if is a value
            $exporter->setXml($currentResponse);
            $responseMessage = "<comment>{$exporter->printByXml()}</comment>" ?? '';
        } else {
            // if is a file
            $this->filePrinter->setFilename($fileAndLine[0]);
            $this->filePrinter->line = $fileAndLine[1];
            $responseMessage = $this->filePrinter->showFile();
        }

        Output::print($responseMessage);
    }

    /**
     * Command to run local or remote commands.
     */
    public function getCommandToSend($currentResponse)
    {
        while (true) {
            // Get a command from the user and send it.
            $line = $this->readLine($currentResponse);
            $command = CommandAdapter::convertCommand($line, DbgpServer::getTransactionId());

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
                Output::print(self::help());
            }
        }
    }

    /**
     * Command to read line (like scanf in C).
     *
     * @return string
     */
    public function readLine($currentResponse)
    {
        if (!preg_match('/\<init xmlns/', $currentResponse)) {
            return Readline::readline();
        }

        return 'continue';
    }
}
