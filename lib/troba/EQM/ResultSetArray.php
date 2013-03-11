<?php

namespace troba\EQM;

class ResultSetArray extends AbstractResultSet implements \ArrayAccess
{
    /**
     * @var object[]
     */
    protected $entities = [];

    /**
     * @var int
     */
    protected $cursor = 0;

    /**
     * @param array $result
     * @param string $classname optional
     */
    public function __construct($result, $classname = '\StdClass')
    {
        $this->entities = $result;
        $this->classname = $classname;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->cursor = 0;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->cursor < count($this->entities);
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->cursor++;
    }

    /**
     * @return object
     */
    protected function getCurrent()
    {
        return $this->entities[$this->cursor];
    }

    /**
     * @return int|mixed
     */
    public function key()
    {
        return $this->cursor;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->entities);
    }

    /**
     * @return ResultSetArray
     */
    public function all()
    {
        return $this;
    }

    /**
     * @return null|object
     */
    public function one()
    {
        $this->next();
        return ($this->valid()) ? $this->entities[$this->cursor] : null;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->entities[$offset]);
    }

    /**
     * @param mixed $offset
     * @return null|object
     */
    public function offsetGet($offset)
    {
        return isset($this->entities[$offset]) ? $this->entities[$offset] : null;
    }

    /**
     * @param mixed $offset
     * @param object $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->entities[] = $value;
        } else {
            $this->entities[$offset] = $value;
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->entities[$offset]);
    }
}
