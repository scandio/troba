<?php

namespace troba\EQM;

class ResultSet implements ResultSetInterface
{
    /**
     * @var \PDOStatement
     */
    protected $pdoStatement;

    /**
     * @var int
     */
    protected $cursor = 0;

    /**
     * @var object
     */
    protected $current = null;

    /**
     * @var int
     */
    protected $count = null;

    /**
     * @var string
     */
    protected $classname = '\StdClass';

    /**
     * @param \PDOStatement $pdoStatement
     * @param string $classname optional
     */
    public function __construct($pdoStatement, $classname = '\StdClass')
    {
        $this->pdoStatement = $pdoStatement;
        $this->count = $this->pdoStatement->rowCount();
        $this->classname = $classname;
        $this->pdoStatement->setFetchMode(\PDO::FETCH_CLASS, $this->classname);
    }

    /**
     * @throws EQMException
     */
    public function rewind()
    {
        if ($this->cursor > 0) {
            throw new EQMException('Rewind is not possible in a result set', 9005);
        }
        $this->next();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->cursor <= $this->count;
    }

    /**
     *
     */
    public function next()
    {
        $this->current = $this->pdoStatement->fetch();
        $this->cursor++;
    }

    /**
     * @return object
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * @return int
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
        return $this->count;
    }

    /**
     * @return array
     */
    public function all()
    {
        $this->cursor = $this->count;
        return $this->pdoStatement->fetchAll();
    }

    /**
     * @return object
     */
    public function one()
    {
        $this->next();
        return ($this->valid()) ? $this->current() : null;
    }
}

