<?php

namespace Codeception\Module;

class UnitHelper extends \Codeception\Module
{
    /**
     * @param $object
     * @param $methodName
     * @param array $parameters
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public static function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
    
    /**
     * @param $object
     * @param $propertyName
     * @param $value
     *
     * @throws \ReflectionException
     */
    public static function setPrivateProperty(&$object, $propertyName, $value)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $refProperty = $reflection->getProperty($propertyName);
        $refProperty->setAccessible(true);
        $refProperty->setValue($object, $value);
    }
    
    /**
     * @param object $object
     * @param string $propertyName
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public static function getPrivateProperty(&$object, $propertyName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $refProperty = $reflection->getProperty($propertyName);
        $refProperty->setAccessible(true);
        return $refProperty->getValue($object);
    }
}
