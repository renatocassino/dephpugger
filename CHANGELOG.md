# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [TODO]
- Refactor client DBGp protocol
- Implement PSR-0 or PSR-4 to project
- Better use for Singleton $config
- Add documentation in each class and methods
- Command 'set' to change the config
- Join verboseMode in one log

## [Unreleased]

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
