<?php

namespace Dephpug;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class Config
{
    private static $_instance = null;
    private $defaultConfig = [
        'server' => [
            'port' => 8888,
            'host' => 'localhost',
            'phpPath' => 'php',
            'path' => null,
            'file' => '',
        ],
        'debugger' => [
            'port' => 9005,
            'host' => 'localhost',
            'lineOffset' => 6,
        ],
        'options' => [
            'verboseMode' => false,
        ],
    ];

    private $config;

    protected function __construct()
    {
    }

    public static function reset()
    {
        self::$_instance = null;
    }

    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new static();
            self::$_instance->configure();
        }

        return self::$_instance;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function configure()
    {
        $config = $this->getConfigFromFile();
        $this->config = array_replace_recursive($this->defaultConfig, $config);
    }

    public function getPathFile()
    {
        return getcwd().'/.dephpugger.yml';
    }

    public function getConfigFromFile()
    {
        $config = [];
        $path = $this->getPathFile();
        if (file_exists($path)) {
            try {
                $config = Yaml::parse(file_get_contents($path));
            } catch (ParseException $e) {
                throw new ParseException('The file .dephpugger.yml is invalid');
            }
        }

        return $config;
    }

    public function __get($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return null;
    }
}
