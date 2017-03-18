<?php

use Dephpug\Config;
use \Codeception\Util\Stub;

class ConfigTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $defaultConfig;

    protected function _before()
    {
        $config = Config::getInstance();
        $this->defaultConfig = $config->getConfig();
    }

    protected function _after()
    {
    }

    // tests
    public function testReplaceOptionsWithNewValues()
    {
        Config::reset();
        $config = Stub::make('\Dephpug\Config', ['getConfigFromFile' => ['server' => ['port' => 123]]]);
        $config->configure();
        $this->assertEquals(123, $config->server['port']);
    }

    public function testKeepKeysNotReplaced()
    {
        Config::reset();
        $config = Stub::make('\Dephpug\Config', ['getConfigFromFile' => ['server' => ['port' => 123]]]);
        $config->configure();
        $this->assertEquals('localhost', $config->server['host']);
    }

    public function testReplaceOptionsWithDataFromYaml()
    {
        Config::reset();
        $config = Stub::make('\Dephpug\Config', ['getPathFile' => __DIR__.'/../data/configValid.yml']);
        $config->configure();
        $this->assertEquals(4005, $config->debugger['port']);
    }

    public function testExceptionIfYamlIsInvalid()
    {
        Config::reset();
        $this->expectException(\Symfony\Component\Yaml\Exception\ParseException::class);
        $config = Stub::make('\Dephpug\Config', ['getPathFile' => __DIR__.'/../data/configInvalid.yml']);
        $config->getConfigFromFile();
    }

    public function testMagicMethodGettingUnexistKey()
    {
        $config = Config::getInstance();
        $this->assertNull($config->notExistKey);
    }

    public function testMagicMethodGettingExistKey()
    {
        $config = Config::getInstance();
        $this->assertEquals($config->getConfig()['server'], $config->server);
    }
}
