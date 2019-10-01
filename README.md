[![Build Status](https://travis-ci.org/tacnoman/dephpugger.svg?branch=master)](https://travis-ci.org/tacnoman/dephpugger) [![Code Climate](https://codeclimate.com/github/tacnoman/dephpug/badges/gpa.svg)](https://codeclimate.com/github/tacnoman/dephpug) [![HitCount](http://hits.dwyl.io/tacnoman/dephpugger.svg)](http://hits.dwyl.io/tacnoman/dephpugger)

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

## Another example
![dephpugger](https://raw.githubusercontent.com/tacnoman/dephpugger/master/images/demo.gif)

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
export PATH=$PATH:$HOME/.config/composer/vendor/bin
```

Now, run `source ~/.bash_profile` and you can run the commands using only `$ dephpugger`.

### In Windows

Sorry for this :/
I don't have Windos SO to run the Dephpugger, but I'm waiting your Pull Request to solve this ;D

But you can use Docker

### Using phar file

```bash
$ wget https://raw.githubusercontent.com/tacnoman/dephpugger/master/dephpugger.phar -o dephpugger
$ chmod +x ./dephpugger
$ mv ./dephpugger /usr/local/bin/dephpugger
```

Or installing via curl

```
$ curl https://raw.githubusercontent.com/tacnoman/dephpugger/master/installer.sh | bash
```

### Install XDebug

To install XDebug in Linux you must run:

```bash
$ sudo apt-get install php-7.2-cli php-dev php-pear
$ sudo pecl install xdebug
```

Find the last line, similar to: `You should add "zend_extension=/usr/lib/php/20170718/xdebug.so" to php.ini` and
copy to your `php.ini` file. To get the `php.ini` file, you can run:

```bash
$ php --info | grep php.ini

Configuration File (php.ini) Path => /etc/php/7.2/cli
Loaded Configuration File => /etc/php/7.2/cli/php.ini
```

Copy the `zend_extension=/usr/lib/php/20170718/xdebug.so` to last line in `/etc/php/7.2/cli/php.ini`.

### Docker

```
$ docker run --rm -p 8888:8888 tacnoman/dephpugger:latest server
```

# Dependencies

- PHP 7.0 or more (not tested in older versions)
- Xdebug activate
- A Plugin for your browser (If you want to debug a web application)

## Plugins for
- [Chrome](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc)
- [Firefox](https://addons.mozilla.org/pt-br/firefox/addon/the-easiest-xdebug/)
- [Safari](https://github.com/benmatselby/xdebug-toggler)
- [Opera](https://addons.opera.com/addons/extensions/details/xdebug-launcher/?display=en)

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

| Command             | Alias | Explanation                                                          |
|---------------------|-------|----------------------------------------------------------------------|
| next                | n     | To run a step over in code                                           |
| step                | s     | To run a step into in code                                           |
| set \<cmd>:\<value> |       | Change verboseMode or lineOffset in runtime                          |
| continue            | c     | To continue script until found another breakpoint or finish the code |
| list                | l     | Show next lines in script                                            |
| list-previous       | lp    | Show previous lines in script                                        |
| help                | h     | Show help instructions                                               |
| $variable           |       | Get a value from a variable                                          |
| $variable = 33      |       | Set a variable                                                       |
| my_function()       |       | Call a function                                                      |
| dbgp(\<command\>)   |       | To run a command in dbgp                                             |
| quit                | q     | Exit the debugger                                                    |

# Configuration (is simple)

The Dephpugger project has default options like a port, host, socket port, etc. You can change this values adding a file `.dephpugger.yml` in root directory project.
You can create in your `.dephpugger.yml` file the configurations. Like this:

```yml
--- 
debugger: 
  host: mysocket.dev # default: 0.0.0.0
  port: 9002 # default: 9005
  lineOffset: 10 # default: 6
  verboseMode: false # default: false
  historyFile: ~/.dephpugger_history # default: .dephpugger_history
server:
  host: myproject.dev # default: 0.0.0.0
  port: 8080 # default: 8888
  path: ./public/ # default: null
  file: index.php # default: null
```

Theese values will replace the default configuration.

# Full documentation

To see the full documentation [click here](http://dephpugger.com).

# How to use with phpunit, behat, codeception and others

The documentation to use, [click here](http://dephpugger.com/Usage/Running_with_phpunit.html).

# Run tests

```sh
$ composer test
$ composer lint
```

# Bugs?
Send me an email or open an issue:

Renato Cassino - Tacnoman - \<renatocassino@gmail.com\>

[See our changelog](https://github.com/tacnoman/dephpugger/blob/master/CHANGELOG.md)

# Articles and Videos

1. [How debug Drupal applications using Dephpugger](https://medium.com/@mosesliao/debug-drupal-using-dephpugger-a0839f878b56)
2. [Dephpugger in Sinapore event](https://www.youtube.com/watch?v=jmMgZ3GqbcQ)
3. [How debug applications with Dephpugger](https://hackernoon.com/how-debug-php-applications-with-dephpugger-98cc234d917c)
