# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## [TODO]
### Add
- Create exception to quit dephpugger
- In config, add option to get a random port
- Refactor client DBGp protocol
- When show type array, show in php format, not json
- Better use for Singleton $config
- Run dephpugger for php scripts in cli

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
