<?php

namespace Dephpug;

use ReflectionClass;

class PluginReflection
{
    private $plugins = [];
    public $interfaceReflection;
    public $core;

    public function __construct(&$core, $interfaceReflection = 'Dephpug\iCommand')
    {
        $this->core = $core;
        $this->interfaceReflection = $interfaceReflection;
        $this->setPlugins();
    }

    public function setPlugins()
    {
        foreach(get_declared_classes() as $klass)
        {
            if($this->isPlugin($klass)) {
                $this->addPlugin($klass);
            }
        }
    }

    public function getPlugins()
    {
        return $this->plugins;
    }

    public function addPlugin($klass)
    {
        $obj = new $klass();
        $obj->setCore($this->core);

        if(!in_array($obj, $this->plugins)) {
            $this->plugins[] = $obj;
        }
    }

    public function isPlugin($klass)
    {
        $reflectionClass = new ReflectionClass($klass);
        $interfaces = array_keys($reflectionClass->getInterfaces());
        return (
            !$reflectionClass->isAbstract() &&
            in_array($this->interfaceReflection, $interfaces)
        );
    }
}
