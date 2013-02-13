<?php

namespace Scandio\ORM;

interface ResultSetInterface extends \Iterator, \Countable
{
    /**
     * @param \PDOStatement $pdoStatement
     * @param string $classname optional
     */
    public function __construct($pdoStatement, $classname = '\StdClass');

    /**
     * @return int the size of the the result set
     */
    public function count();

    /**
     * @return array all objects as an array
     */
    public function all();

    /**
     * @return object
     */
    public function one();
}