<?php

namespace Dephpug;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class Config
{
    private static $_instance;
    private $defaultConfig = [
        'server' => [
            'port' => 8888,
            'host' => 'localhost',
            'phpPath' => 'php'
        ],
        'debugger' => [
            'port' => 9005,
            'host' => 'localhost',
            'forceBreakFirstLine' => true
        ]
    ];

    protected function __construct() {
    }

    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new static();
            self::$_instance->configure();
        }

        return self::$_instance;
    }

    private function configure() {
        $path = getcwd() . '/.dephpugger.yml';
        $config = [];
        if(file_exists($path)) {
            try {
                $config = Yaml::parse(file_get_contents($path));
            } catch(ParseException $e) {
                die($e->getMessage());
            }
        }
        $this->config = array_replace_recursive($this->defaultConfig, $config);
    }

    public function __get($key) {
        if(isset($this->config[$key])) {
            return $this->config[$key];
        }
        return null;
    }
}