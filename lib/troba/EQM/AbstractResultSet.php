<?php

namespace troba\EQM;

abstract class AbstractResultSet implements \Iterator, \Countable
{
    /**
     * @var array
     */
    protected $relations = [];

    /**
     * @var string
     */
    protected $classname = '\StdClass';

    /**
     * @param string $property
     * @param AbstractResultSet $resultSet
     * @param array|string $keys
     */
    public function relate($property, $resultSet, $keys)
    {
        $keys = is_string($keys) ? [$keys] : $keys;
        foreach ($resultSet as $object) {
            $this->relations[$property][$object->{$keys[0]}][] = $object;
        }
    }

    /**
     * @return object
     */
    public function current()
    {
        $current = $this->getCurrent();
        if (count($this->relations) > 0) {
            $primary = EQM::tableMeta($this->classname)->getPrimary();
            $key = array_pop($primary);
            foreach ($this->relations as $property => $relation) {
                $current->{$property} = $relation[$current->{$key}];
            }

        }
        return $current;
    }

    /**
     * @param \PDOStatement|array $result
     * @param string $classname optional
     */
    public abstract function __construct($result, $classname = '\StdClass');

    /**
     * @return object
     */
    protected abstract function getCurrent();

    /**
     * @return void
     */
    public abstract function rewind();

    /**
     * @return bool
     */
    public abstract function valid();

    /**
     * @return void
     */
    public abstract function next();

    /**
     * @return mixed
     */
    public abstract function key();
    /**
     * @return int the size of the the result set
     */
    public abstract function count();

    /**
     * @return array all objects as an array
     */
    public abstract function all();

    /**
     * @return object
     */
    public abstract function one();
}
