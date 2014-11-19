<?php

namespace troba\EQM;

class ResultSet extends AbstractResultSet {

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
     * @param \PDOStatement $pdoStatement
     * @param string $classname optional
     */
    public function __construct($pdoStatement, $classname = '\StdClass') {
        $this->pdoStatement = $pdoStatement;
        $this->count = $this->pdoStatement->rowCount();
        $this->classname = $classname;
        $this->pdoStatement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->classname);
    }

    /**
     * @throws EQMException
     */
    public function rewind() {
        if ($this->cursor > 0) {
            throw new EQMException('Rewind is not possible in a result set', 9005);
        }
        $this->next();
    }

    /**
     * @return bool
     */
    public function valid() {
        return $this->cursor <= $this->count;
    }

    /**
     * @return void
     */
    public function next() {
        $this->current = $this->pdoStatement->fetch();
        $this->cursor++;
    }

    /**
     * @return object
     */
    protected function getCurrent() {
        return $this->current;
    }

    /**
     * @return int
     */
    public function key() {
        return $this->cursor;
    }

    /**
     * @return int
     */
    public function count() {
        return $this->count;
    }

    /**
     * @return AbstractResultSet
     */
    public function all() {
        $this->cursor = $this->count;
        return new ResultSetArray($this->pdoStatement->fetchAll(), $this->classname);
    }

    /**
     * @return object
     */
    public function one() {
        $this->next();
        return ($this->valid()) ? $this->current() : null;
    }
}

