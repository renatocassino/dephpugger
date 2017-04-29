# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [TODO]
- Add documentation in each class and methods
- Create .phar file

## [Unreleased]

## [1.1 - 2017-04-29]
### Added
- Getting global variables in property get
- Tests

## [1.0.2-rc1 - 2017-04-26]
### Added
- Class to client for Dbgp
- Tests for dbgp Server and Client
- Command 'set' to change the config

### Changed
- Namespace for client and server Dbgp
- Removing phpPath and add PHP_BINARY

### Fixed
- Command cli printing content

## [1.0.1-alpha]
### Fixed
- Bug in autoload in global

## [1.0.0-alpha]
### Added
- New architecture
- Tests for commands
- Tests for MessageEvent
- One class for each command in debugger
- One class for each xml received in DBGP
- New flow execution
- Core with REPL
- Class with reflection mode to get all plugins (commands and parsers)
- Readline with history

### Changed
- Refactor client DBGp protocol
- Remove singleton in $config
- Output in a single class

## [0.5.1] - 2017-03-24
### Fixed
- Bug in autoload

## [0.5.0] - 2017-03-22
### Added
- Add command Help
- Add history to debugger
- Create a unique class to print with symfony color
- Command `list-previous` to show previous lines in file

## [0.4.1] - 2017-03-19
### Changed
- Attribute verboseMode now is in debugger, not in options
- Command l to list
- If send command emtpy, repeat the last command sent

### Fixed
- Bug in command requirements and info

## [0.4.0] - 2017-03-19
### Added
- If send empty string, repeat the last command in debugger

### Changed
- Using DIRECTORY_SEPARATOR instead of '/' (windows support)
- Refactor in DBGP server
- Separate responsability to parse messages in one class

### Fixed
- The code were using 127.0.0.1 in debugger server instead of variable setted

## [0.3.1] - 2017-03-11
### Added
- Sent messages in verboseMode

### Fixed
- Bug with set a variable

## [0.3.0] - 2017-03-11
### Added
- Support to show attributes in object var
- Test for string
- Get response when call a method

### Changed
- Refactor in FilePrinter to use xml instead of regex
- Use xml in tests

### Fixed
- Bug with objects debugger. Fixed when use var $this

## [0.2.1] - 2017-03-06
### Fixed
- Bug with path and without file

## [0.2.0] - 2017-03-06
### Added
- Params file and path

## [0.1.2] - 2017-03-01
### Added
- Catch error if try show value of unexist var

## [0.1.1] - 2017-03-01
### Added
- When show type array, show in php format, not xml
- When show type object, show the name of the class
- More tests for CommandAdapter
- Command to get info about xdebug
- Add option to offset lines in .dephpugger.yml

### Fixed
- Add type float to show variable in array

## [0.0.3] - 2017-03-01
### Added
- Add circleci support
- Refactor client DBGp protocol - [WIP]
- Class to get variable in phpinfo();
- Create exception to quit dephpugge
- Run dephpugger for php scripts in cli
- Counter changing the transactionId

## Changed
- Algorithm to get data from DBGPp protocol
- Quit command in class CommandAdapter

## Fixed
- Bug when stop a script
- Fix the stop when debugger start

## [0.0.2]
### Changed
- Use symfony/console for all commands

### Added
- Explanation how to use the .dephpugger.yml
- File .dephpugger.yml in root path to change default configs
- Add type to return format variable
- Add a (ridiculuous) logo to github
- Can run in verbose mode to print all xml DBGp
- Add command to check requirements

## [0.0.1]
### Added
- TravisCI integration
- Codeclimate integration
- Returning formated values instead of XML DBGp format
- Color code with symfony/console
- Figlet to write Dephpugger in terminal for server and debugger
- Create README explain the project with an image (screenshot)
- Install monolog to log application
- Set a current version in a separated file
- Create changelog
- Tests for commands and FilePrinter
- FilePrinter coloring code with reserved words
- FilePrinter class to print a file in terminal
- Command conversor to IDE commands
- Possibility to run IDE commands to DBGp
- Breakpoints
- Install codeception to make tests
- Binary file to run client websocket to DBGp protocol
- Binary file to run php server built in connecting websocket
- PSR-0 implemented
- Added to packagist
- Composer component to install
- Client to DBGp protocol
