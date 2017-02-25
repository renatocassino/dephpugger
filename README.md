# PHP DEBUG

To use Xdebug in PHPStorm, you must put this configuration:

- Run/Debug Configuration > Click in "+".
- Select PHP Built-In Web Server

In server Configuration:
- Host: localhost
- Port: 8080

In Command Line:
- Interpreter options: -dxdebug.remote_enable=1 -dxdebug.remote_mode=req -dxdebug.remote_port=9000 -dxdebug.remote_host=127.0.0.1 -dxdebug.remote_connect_back=0

Click in Apply/Ok.
Push Play button.
Click on Start Listening for Php Debug Connections.

Download this extension:
https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc

Open helper and select PhpStorm in IDE key.


In Terminal you can run:
/usr/bin/php -S localhost:8888 -t /var/www/phpdebug -dxdebug.remote_enable=1 -dxdebug.remote_mode=req -dxdebug.remote_port=9000 -dxdebug.remote_host=127.0.0.1 -dxdebug.remote_connect_back=0
