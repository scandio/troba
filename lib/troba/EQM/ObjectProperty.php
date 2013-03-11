<?php

namespace troba\EQM;

class ObjectProperty
{
    /**
     * return the value of an object property. It does not matter whether the
     * property is public, private or protected
     *
     * @param string $property
     * @param object|string $objectOrClass
     * @return mixed
     */
    public static function get($property, $objectOrClass)
    {
        $result = null;
        if (is_string($objectOrClass) && class_exists($objectOrClass))
            $objectOrClass = new $objectOrClass();
        $reflection = new \ReflectionObject($objectOrClass);
        if ($reflection->hasProperty($property)) {
            $property = $reflection->getProperty($property);
            $property->setAccessible(true);
            return $property->getValue($objectOrClass);
        }
        return $result;
    }

    /**
     * Sets the property of an object: It does not matter whether the property
     * is public, private or protected
     *
     * @param string $property
     * @param mixed $value
     * @param $object
     * @return void
     */
    public static function set($property, $value, $object)
    {
        $reflection = new \ReflectionObject($object);
        if ($reflection->hasProperty($property)) {
            $property = $reflection->getProperty($property);
            $property->setAccessible(true);
            $property->setValue($object, $value);
        } else {
            $object->{$property} = $value;
        }
    }
}
