<?php

namespace troba\Model;

use troba\EQM\EQMException;

trait Getters
{
    /**
     * calls Class::$name()
     *
     * @param string $name the name of a requested property
     * @return mixed the result
     */
    public function __get($name)
    {
        return $this->__call($name);
    }

    /**
     * checks whether a get method get<$Name>() exists and calls it
     *
     * @param string $name
     * @param array $args optional
     * @throws \Exception
     * @return mixed
     */
    public function __call($name, $args = [])
    {
        if (method_exists($this, $method = 'get' . ucfirst($name))) {
            return $this->{$method}($args);
        } else {
            throw new EQMException('Method or property does not exists');
        }
    }
}