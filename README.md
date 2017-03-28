[![Build Status](https://travis-ci.org/tacnoman/dephpugger.svg?branch=master)](https://travis-ci.org/tacnoman/dephpugger) [![Code Climate](https://codeclimate.com/github/tacnoman/dephpug/badges/gpa.svg)](https://codeclimate.com/github/tacnoman/dephpug)

<img src="https://raw.githubusercontent.com/tacnoman/dephpugger/master/images/logo.png" alt="logo" title="Dephpugger logo" height="500">

# What is Dephpugger?

Dephpugger (read depugger) is an open source lib to make a debug in php direct in terminal, without necessary configure an IDE. The dephpugger run in Php Built in Server using another command. You can use for:

## Web applications
### Lumen in example
![dephpugger web](https://raw.githubusercontent.com/tacnoman/dephpugger/master/images/dephpugger-web.gif)
`Image 1.0 - Screenrecord for debug web`

## Cli Scripts
![dephpugger](https://raw.githubusercontent.com/tacnoman/dephpugger/master/images/dephpugger.gif)
`Image 1.1 - Screenrecord for debug cli scripts`

# Install

To install you must run this code (using the composer).

```sh
$ composer require tacnoman/dephpugger
```

## Install globally
### In Linux or Mac Os X

Run this command:

```sh
$ composer global require tacnoman/dephpugger
```

And add in your ~/.bash_profile.

```
export PATH=$PATH:$HOME/.composer/vendor/bin
```

Now run `source ~/.bash_profile` and you can run the commands using only `$ dephpugger`.

# Dependencies

- PHP 7.0 or more (not tested in older versions)
- Xdebug activate
- [This plugin for chrome](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc)
- [Or this plugin for Firefox](https://addons.mozilla.org/pt-br/firefox/addon/the-easiest-xdebug/)

You can run this commands to check your dependencies:

```sh
$ vendor/bin/dephpugger requirements
$ vendor/bin/dephpugger info # To get all values setted in xDebug

# Or in global

$ dephpugger requirements
$ dephpugger info
```

# Usage

To usage you must (after installation) run two binaries in `vendor/bin` folder.

```sh
$ php vendor/bin/dephpugger debugger # Debugger waiting debug
$ php vendor/bin/dephpugger server   # Server running in port 8888

# Or in global

$ dephpugger debugger
$ dephpugger server
```

You must run in two different tabs (in next version you'll can run in an uniq tab).
After run theese commands, you need to put the follow line in your code:

```php
<?php
# ...
xdebug_break(); # This code is a breakpoint like ipdb in Python and Byebug in Ruby
# ....
```

Now, you can open in your browser the page (localhost:8888/[yourPage.php]).
When you request this page your terminal will start in breakpoint (like the image 1.0).

To debugger a php script, you could run:
```sh
$ php vendor/bin/dephpugger cli myJob.php

# Or in global

$ dephpugger cli myJob.php
```
## Comands after run

When you stop in a breakpoint you can make theese commands:

| Command           | Alias | Explanation                                                          |
|-------------------|-------|----------------------------------------------------------------------|
| next              | n     | To run a step over in code                                           |
| step              | s     | To run a step into in code                                           |
| continue          | c     | To continue script until found another breakpoint or finish the code |
| list              | l     | Show next lines in script                                            |
| list-previous     | lp    | Show previous lines in script                                        |
| help              | h     | Show help instructions                                               |
| $variable         |       | Get a value from a variable                                          |
| $variable = 33    |       | Set a variable                                                       |
| my_function()     |       | Call a function                                                      |
| dbgp(\<command\>) |       | To run a command in dbgp                                             |
| quit              | q     | Exit the debugger                                                    |

# Configuration (is simple)

The Dephpugger project has default options like a port, host, socket port, etc. You can change this values adding a file `.dephpugger.yml` in root directory project.

The default config is:

```php
    $defaultConfig = [
        'server' => [
            'port' => 8888, # Port to your php built in web server
            'host' => 'localhost', # Host to your php build in web server
            'phpPath' => 'php', # Path to run php
            'path' => null, # Path to folder (param -t in php command)
            'file' => ''
        ],
        'debugger' => [
            'port' => 9005, # Port to socket
            'host' => 'localhost', # Host to socket,
            'lineOffset' => 6, # Number of line offset to show in debugger
            'verboseMode' => false, # If true, show all messages from DBGp (only for dephpugger developers)
            'historyFile' => '.dephpugger_history', # File with history to use use commands in debugger
        ]
    ];
```

You can replace in your `.dephpugger.yml` file. Like this:

```yml
--- 
debugger: 
  host: mysocket.dev
  port: 9002
  lineOffset: 10
  path: ./public/
  file: index.php
  verboseMode: false
  historyFile: ~/.dephpugger_history
server: 
  host: myproject.dev
  phpPath: /usr/local/bin/php
  port: 8080
```

Theese values will replace the default configuration.

# DEVELOPING YET!

[Documentation in github pages](https://tacnoman.github.io/dephpugger)

# Run tests

```sh
$ codecept run unit
```

# Bugs?
Send me an email or open an issue:

Renato Cassino - Tacnoman - \<renatocassino@gmail.com\>

[See our changelog](https://tacnoman.github.io/dephpugger/CHANGELOG)
