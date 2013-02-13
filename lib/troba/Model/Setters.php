<?php

namespace troba\Model;

trait Setters
{
    /**
     * @param string $name property name
     * @param mixed $value the value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        if (method_exists($this, $method = 'set' . ucfirst($name))) {
            $this->{$method}($value);
        } else {
            throw new \Exception('private or protected properties are not accessible');
        }
    }
}