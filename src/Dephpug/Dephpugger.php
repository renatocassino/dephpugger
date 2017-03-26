<?php

namespace Dephpug;

class Dephpugger
{
    public static $VERSION = '0.5.1';

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
        $dbgpServer = new DbgpServer();
        $dbgpServer->startClient();
        $dbgpServer->getResponse();
        $messageParse = new MessageParse();
        $config = Config::getInstance();

        try {
            do {
                $dbgpServer->printResponse();
                if ($config->debugger['verboseMode']) {
                    $message = $messageParse->printIfIsStream($dbgpServer->getCurrentResponse());
                    if($message) {
                        Output::print($message);
                    }
                }

                // Received response saying we're stopping.
                if ($messageParse->isStatusStop($dbgpServer->getCurrentResponse())) {
                    Output::print("<comment>-- Request ended, restarting... --</comment>\n");

                    return;
                }

                // Ask command to dev
                $command = $dbgpServer->getCommandToSend();
                $dbgpServer->sendCommand($command);
            } while (true);
        } catch (Exception\QuitException $e) {
        }

        $dbgpServer->closeClient();
    }
}
