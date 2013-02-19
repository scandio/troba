<?php

namespace troba\EQM;

/**
 * A OO wrapper with chaining for EQM::query
 */
class Query
{
    /**
     * @var null|object|string the class name or an object
     */
    protected $entity = null;

    /**
     * @var null|string the list of database fields to read
     */
    protected $fields = null;

    /**
     * @var null|object|string the class name or an object
     */
    protected $from = null;

    /**
     * @var array|object single join or list of joins
     */
    protected $joins = [];

    /**
     * @var null|string the where query
     */
    protected $query = null;

    /**
     * @var array|mixed simple value or array or assoc array of param values
     */
    protected $queryParams = [];

    /**
     * @var array|string the group by column(s)
     */
    protected $group = [];

    /**
     * @var null|string the having part of a sql query
     */
    protected $having = null;

    /**
     * @var array|mixed simple value or array or assoc array of param values
     */
    protected $havingParams = [];

    /**
     * @var array|string the order by array or single value
     */
    protected $order = [];

    /**
     * @var null|int the number of records to be read
     */
    protected $limit = null;

    /**
     * @var null|int the starting point for reading results
     */
    protected $offset = null;

    /**
     * @param null|object|string $entity optional
     */
    public function __construct($entity = null)
    {
        $this->entity = ($entity) ? $entity : '\StdClass';
    }

    /**
     * @param string $fields the fields to be read
     * @return Query
     */
    public function select($fields = null)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param object|string $entity the class name or an object
     * @param string $query the ON part of a joined query
     * @return Query
     */
    public function innerJoin($entity, $query)
    {
        $this->joins[] = EQM::join(EQM::INNER_JOIN, $entity, $query);
        return $this;
    }

    /**
     * @param object|string $entity the class name or an object
     * @param string $query the ON part of a joined query
     * @return Query
     */
    public function leftJoin($entity, $query)
    {
        $this->joins[] = EQM::join(EQM::LEFT_JOIN, $entity, $query);
        return $this;
    }

    /**
     * @param object|string $entity the class name or an object
     * @param string $query the ON part of a joined query
     * @return Query
     */
    public function rightJoin($entity, $query)
    {
        $this->joins[] = EQM::join(EQM::RIGHT_JOIN, $entity, $query);
        return $this;
    }

    /**
     * @param object|string $entity the class name or an object
     * @return Query
     */
    public function from($entity)
    {
        $this->from = $entity;
        return $this;
    }

    /**
     * @param string $query the WHERE part of a sql statement
     * @param array|mixed $params optional simple value or array or assoc array of param values
     * @return Query
     */
    public function where($query, $params = [])
    {
        $this->query = $query;
        $this->queryParams = $params;
        return $this;
    }

    /**
     * @param string $query the WHERE part of a sql statement
     * @param array|mixed $params optional simple value or array or assoc array of param values
     * @return Query
     */
    public function andWhere($query, $params = [])
    {
        $this->query .= ' AND ' . $query;
        $this->queryParams = array_merge($this->queryParams, $params);
        return $this;
    }

    /**
     * @param string $query the WHERE part of a sql statement
     * @param array|mixed $params optional simple value or array or assoc array of param values
     * @return Query
     */
    public function orWhere($query, $params = [])
    {
        $this->query .= ' OR ' . $query;
        $this->queryParams = array_merge($this->queryParams, $params);
        return $this;
    }

    /**
     * @param array|string $group the GROUP BY part of a sql statement
     * @return Query
     */
    public function groupBy($group = [])
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @param string $query the HAVING part of a sql statement
     * @param array|mixed $params optional simple value or array or assoc array of param values
     * @return Query
     */
    public function having($query, $params = [])
    {
        $this->having = $query;
        $this->havingParams = $params;
        return $this;
    }

    /**
     * @param array|string $order the ORDER BY part of the sql statement
     * @return Query
     */
    public function orderBy($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @param int $limit the number of records to be returned
     * @param int|null $offset optional the starting point
     * @return Query
     */
    public function limit($limit, $offset = null)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param int|null $limit optional the number of records to be returned
     * @param int|null $offset optional the starting point
     * @return ResultSetInterface
     */
    public function result($limit = null, $offset = null)
    {
        if (!is_null($limit || $offset)) {
            $this->limit(
                ($limit) ? $limit : $this->limit,
                ($offset) ? $offset : $this->offset
            );
        }
        return EQM::queryByArray([
            'entity' => $this->entity,
            'fields' => $this->fields,
            'from' => $this->from,
            'join' => $this->joins,
            'query' => $this->query,
            'params' => $this->queryParams,
            'having' => $this->having,
            'havingParams' => $this->havingParams,
            'group' => $this->group,
            'order' => $this->order,
            'limit' => $this->limit,
            'offset' => $this->offset
        ]);
    }

    /**
     * Shorthand for Query::result()->all()
     *
     * @param int $limit optional
     * @param int $offset optional
     * @return array
     */
    public function all($limit = null, $offset = null)
    {
        return $this->result($limit, $offset)->all();
    }

    /**
     * Shorthand for Query::result()->one
     *
     * @param int $limit
     * @param int $offset
     * @return object
     */
    public function one($limit = null, $offset = null)
    {
        return $this->result($limit, $offset)->one();
    }
}
