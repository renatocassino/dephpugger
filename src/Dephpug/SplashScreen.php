<?php

namespace Dephpug;

// Function to splash screen
function splashScreen($word='Server') {
    $version = 'v' . \Dephpug\Dephpugger::$VERSION;
    return <<<EOL
<info>
  _____             _                                       
 |  __ \           | |                                      
 | |  | | ___ _ __ | |__  _ __  _   _  __ _  __ _  ___ _ __ 
 | |  | |/ _ \ '_ \| '_ \| '_ \| | | |/ _` |/ _` |/ _ \ '__|
 | |__| |  __/ |_) | | | | |_) | |_| | (_| | (_| |  __/ |   
 |_____/ \___| .__/|_| |_| .__/ \__,_|\__, |\__, |\___|_|   
             | |         | |           __/ | __/ |          
             |_|         |_|          |___/ |___/           </info>

                                   $word - Version: <fg=cyan>$version</>

EOL;
}
