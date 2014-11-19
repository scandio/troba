<?php

namespace troba\EQM;

class ObjectProperty {

    /**
     * return the value of an object property. It does not matter whether the
     * property is public, private or protected
     *
     * @param string $property
     * @param object $object
     * @return mixed
     */
    public static function get($property, $object) {
        $result = null;
        $reflection = new \ReflectionObject($object);
        if ($reflection->hasProperty($property)) {
            $property = $reflection->getProperty($property);
            $property->setAccessible(true);
            $result = $property->getValue($object);
        }
        return $result;
    }

    /**
     * returns the default value of a property in a class
     *
     * @param string $property
     * @param string $className
     * @return mixed
     */
    public static function getFromClass($property, $className) {
        $result = null;
        $reflection = new \ReflectionClass($className);
        $properties = $reflection->getDefaultProperties();
        if (array_key_exists($property, $properties)) {
            $result = $properties[$property];
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
    public static function set($property, $value, $object) {
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
