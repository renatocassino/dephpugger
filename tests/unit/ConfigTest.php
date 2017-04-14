<?php

use Dephpug\Config;
use \Codeception\Util\Stub;

class ConfigTest extends \Codeception\Test\Unit
{
    protected $defaultConfig;

    protected function _before()
    {
        $config = new Config();
        $this->defaultConfig = $config->getConfig();
    }

    // tests
    public function testReplaceOptionsWithNewValues()
    {
        $config = Stub::make('\Dephpug\Config', ['getConfigFromFile' => ['server' => ['port' => 123]]]);
        $config->configure();
        $this->assertEquals(123, $config->server['port']);
    }

    public function testKeepKeysNotReplaced()
    {
        $config = Stub::make('\Dephpug\Config', ['getConfigFromFile' => ['server' => ['port' => 123]]]);
        $config->configure();
        $this->assertEquals('localhost', $config->server['host']);
    }

    public function testReplaceOptionsWithDataFromYaml()
    {
        $config = Stub::make('\Dephpug\Config', ['getPathFile' => __DIR__.'/../data/configValid.yml']);
        $config->configure();
        $this->assertEquals(4005, $config->debugger['port']);
    }

    public function testExceptionIfYamlIsInvalid()
    {
        $this->expectException(\Symfony\Component\Yaml\Exception\ParseException::class);
        $config = Stub::make('\Dephpug\Config', ['getPathFile' => __DIR__.'/../data/configInvalid.yml']);
        $config->getConfigFromFile();
    }

    public function testMagicMethodGettingUnexistKey()
    {
        $config = new Config();
        $this->assertNull($config->notExistKey);
    }

    public function testMagicMethodGettingExistKey()
    {
        $config = new Config();
        $this->assertEquals($config->getConfig()['server'], $config->server);
    }
}
