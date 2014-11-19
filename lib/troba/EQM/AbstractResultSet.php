<?php

namespace troba\EQM;

abstract class AbstractResultSet implements \Iterator, \Countable {

    /**
     * @var array
     */
    protected $relations = [];

    /**
     * @var array
     */
    protected $keys = [];

    /**
     * @var string
     */
    protected $cardinality = 'many';

    /**
     * @var string
     */
    protected $classname = '\StdClass';

    /**
     * @param string $property
     * @param AbstractResultSet $resultSet
     * @param array|string $keys
     * @param string $cardinality optional [many|one]
     */
    public function relate($property, $resultSet, $keys, $cardinality = 'many') {
        $this->keys = $keys;
        $this->cardinality = $cardinality;
        foreach ($resultSet as $object) {
            $this->relations[$property][ObjectProperty::get($keys[1], $object)][] = $object;
        }
    }

    /**
     * @return object
     */
    public function current() {
        $current = $this->getCurrent();
        if (count($this->relations) > 0) {
            foreach ($this->relations as $property => $relation) {
                $resultSet = new ResultSetArray($relation[$current->{$this->keys[0]}], $this->classname);
                ObjectProperty::set($property, ($this->cardinality == 'one') ? $resultSet[0] : $resultSet, $current);
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
