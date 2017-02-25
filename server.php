<?php

require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Server implements MessageComponentInterface {
    public $conn;
    public $log;

    public function onOpen(ConnectionInterface $conn) {
        $this->conn = $conn;
        $this->log = new Logger('name');
        $this->log->pushHandler(new StreamHandler('./log1.log', Logger::WARNING));

        $this->log->warning('onOpen');
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $this->log->warning('onMessage');
        $f = fgets(STDIN);
        echo $f;
        $this->log->warning('Message: ' . $msg);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $this->log->warning('onError');
        $this->log->warning('Error: ' . $e->getMessage());
    }

    public function onClose(ConnectionInterface $conn) {
        $this->log->warning('onClose');
    }
}

$server = IoServer::factory(
    new Server(),
    9005
);

$server->run();