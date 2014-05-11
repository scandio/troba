<?php

namespace troba\EQM;

class EQM extends PDOWrapper
{
    /**
     * Constant for inner join used by EQM::join()
     */
    const INNER_JOIN = 'INNER JOIN';

    /**
     * Constant for left join used by EQM::join()
     */
    const LEFT_JOIN = 'LEFT JOIN';

    /**
     * Constant for right join used by EQM::join()
     */
    const RIGHT_JOIN = 'RIGHT JOIN';

    /**
     * Constant for convention handler used as key in EQM::initialize()
     */
    const CONVENTION_HANDLER = 'convention_handler';

    /**
     * Constant for sql builder used as key in EQM::initialize()
     */
    const SQL_BUILDER = 'sql_builder';

    /**
     * @var array contains all already read table meta data per connection
     *            like $metaCache[<connection>][<table]
     */
    protected static $metaCache = [];

    /**
     * @var ConventionHandlerInterface[] any class that implements the interface
     */
    protected static $conventionHandler = [];

    /**
     * To initialize the first connection you need at least the PDO parameters in a assoc array
     * It's possible to use more parameters. Here ist the complete array
     *
     *  $config =   [
     *                  EQM::CONVENTION_HANDLER => <object that implements ConventionHandlerInterface>
     *                  EQM::SQL_BUILDER => <object that implements SqlBuilderInterface>
     *              ]
     *
     * @param \PDO $pdo a valid PDO connection object
     * @param array $config allowed keys [run_mode, result_Set_class (class name)
     *                      convention_handler (object), sql_builder (object)]
     * @param string $connectionName optional for multiple connections 'default' is the standard
     */
    public static function initialize($pdo, $config = [], $connectionName = 'default')
    {
        parent::initialize($pdo, $config, $connectionName);
        static::$metaCache[$connectionName] = [];
        if (array_key_exists(static::CONVENTION_HANDLER, $config)
            && $config[static::CONVENTION_HANDLER] instanceof ConventionHandlerInterface
        ) {
            static::$conventionHandler[$connectionName] = $config[EQM::CONVENTION_HANDLER];
        } else {
            static::$conventionHandler[$connectionName] = new DefaultConventionHandler();
        }
        if (array_key_exists(static::SQL_BUILDER, $config)
            && $config[static::SQL_BUILDER] instanceof SqlBuilderInterface
        ) {
            static::$sqlBuilder[$connectionName] = $config[static::SQL_BUILDER];
        } else {
            static::$sqlBuilder[$connectionName] = new MySqlBuilder;
        }
    }

    /**
     * @var SqlBuilderInterface[] a SqlBuilder based on SqlBuilderInterface
     */
    protected static $sqlBuilder = [];

    /**
     * checks if a connection is already initialized
     *
     * @param string $connectionName
     * @return bool
     */
    public static function isInitialized($connectionName = 'default')
    {
        return !empty(static::$db[$connectionName]) && static::$db[$connectionName] instanceof \PDO;
    }

    /**
     * checks the object properties against the table columns and returns an array
     * that contains only the allowed data. Optional it's possible to eliminate the
     * primary key and auto increment fields
     *
     * @param $object
     * @param null|string $eliminate allowed value are primary and auto_increment
     * @return array
     */
    protected static function objectTableData($object, $eliminate = null)
    {
        $tableMeta = static::tableMeta($object);
        $data = [];
        foreach ($tableMeta->getColumns() as $column) {
            if (property_exists($object, $column->name)) {
                if ($eliminate == 'auto_increment' && $column->isAutoIncrement()) {
                    # auto_increment
                } elseif ($eliminate == 'primary' && $column->isPrimary()) {
                    # primary
                } else {
                    # TODO ask better
                    # the two if areas above are empty
                    $data[$column->name] = ObjectProperty::get($column->name, $object);
                }
            }
        }
        return $data;
    }

    /**
     * Inserts an object in the database table. This method checks the primary keys an the auto
     * increment ids if they exists. it returns the manipulated object itself.
     *
     * @param $object
     * @return null|object
     */
    public static function insert($object)
    {
        static::handleEvent('preInsert', $object);
        $tableMeta = static::tableMeta($object);
        $dataParams = static::objectTableData($object, 'auto_increment');
        $sql = static::$sqlBuilder[static::$activeConnection]->insert(static::tableName($object), $dataParams);
        $result = static::nativeExecute($sql, $dataParams, $tableMeta->hasAutoIncrement());
        if ($result) {
            if ($tableMeta->hasAutoIncrement()) {
                ObjectProperty::set($tableMeta->getAutoIncrement(), $result, $object);
            }
            static::handleEvent('postInsert', $object);
            $result = $object;
        } else {
            $result = null;
        }
        return $result;
    }

    /**
     * Updates an object if you've changed the primary key the method
     * tries to update another record in the database table.
     *
     * @param $object
     * @throws EQMException
     * @return bool
     */
    public static function update($object)
    {
        static::handleEvent('preUpdate', $object);
        $dataParams = static::objectTableData($object, 'primary');
        $primaryQuery = static::primaryQuery($object, null, 'eqm_key_');
        static::updateQuery($object, $dataParams, $primaryQuery->query, $primaryQuery->params);
        static::handleEvent('postUpdate', $object);
        return true;
    }

    /**
     * Updates one or more records by a query
     *
     * @param object|string $objectOrClass the class/table to be modified
     * @param $dataParams
     * @param string|null $query optional
     * @param array $queryParams
     * @return bool
     */
    public static function updateQuery($objectOrClass, $dataParams, $query = null, $queryParams = [])
    {
        $result = false;
        if ($dataParams) {
            $sql = static::$sqlBuilder[static::$activeConnection]
                ->update(static::tableName($objectOrClass), $query, $dataParams);
            $result = static::nativeExecute($sql, array_merge($queryParams, $dataParams));
        }
        return $result;
    }

    /**
     * Deletes an object by its primary key
     *
     * @param $object
     * @return bool
     */
    public static function delete($object)
    {
        static::handleEvent('preDelete', $object);
        $primaryQuery = static::primaryQuery($object);
        $result = static::deleteQuery($object, $primaryQuery->query, $primaryQuery->params);
        static::handleEvent('postDelete', $object);
        return $result;
    }

    /**
     * delete a number of records by a query. there are no event handlers called in this methos
     *
     * @param object|string $objectOrClass
     * @param string|null $query optional
     * @param array $params
     * @return bool
     */
    public static function deleteQuery($objectOrClass, $query = null, $params = [])
    {
        $sql = static::$sqlBuilder[static::$activeConnection]->delete(static::tableName($objectOrClass), $query);
        return static::nativeExecute($sql, $params);
    }

    /**
     * Returns the primary query for an object or a class if $params is empty
     * the parameters of the object will be used
     *
     * @param object|string $objectOrClass
     * @param $objectOrClass
     * @param string|null $params optional
     * @param string $prefix
     * @throws EQMException
     * @return object|string the result contains a query and a params parameter
     */
    protected static function primaryQuery($objectOrClass, $params = null, $prefix = '')
    {
        $objectOrClass = (is_string($objectOrClass)) ? new $objectOrClass() : $objectOrClass;
        $query = '';
        $queryParams = [];
        # TODO check for assoc arrays necessary
        $isNotAssoc = false;
        if (!is_null($params) && !is_array($params)) {
            $params = [$params];
            $isNotAssoc = true;
        }
        foreach (static::tableMeta($objectOrClass)->getPrimary() AS $primary) {
            $query .= ((!empty($query)) ? ' AND ' : ' ') . $primary . ' = :' . $prefix . $primary;
            if (is_null($params)) {
                $queryParams[$prefix . $primary] = ObjectProperty::get($primary, $objectOrClass);
                if ($queryParams[$prefix . $primary] == null) {
                    throw new EQMException('null is not a valid parameter for a primary query', 9003);
                }
            } else if ($isNotAssoc) {
                $queryParams[$prefix . $primary] = array_shift($params);
            } else {
                $queryParams[$prefix . $primary] = $params[$primary];
            }
        }
        return (object)['query' => $query, 'params' => $queryParams];
    }

    /**
     * returns a entity object of the requested class by the primary keys of the table
     *
     * @param object|string $objectOrClass
     * @param array|mixed $params
     * @return object
     */
    public static function queryByPrimary($objectOrClass, $params)
    {
        $primaryQuery = static::primaryQuery($objectOrClass, $params);
        return static::queryByArray([
            'entity' => $objectOrClass,
            'query' => $primaryQuery->query,
            'params' => $primaryQuery->params
        ])->one(1);
    }

    /**
     * Returns a chaining query object that calls queryByArray if result() is called
     *
     * @param object|string $objectOrClass
     * @return Query
     */
    public static function query($objectOrClass = '\StdClass')
    {
        return new Query($objectOrClass);
    }

    /**
     * Returns a SQL statement for a (joined) select
     *
     * Keys for the queryParams array:
     *
     *      'entity' => object or class name - it is used as FROM if 'from' is not set
     *      'fields' => table columns to be returned * is the default
     *      'from' => object or class name it overrides FROM independent from 'entity'
     *      'join' => single join object created with EQM::join() or array of them
     *      'query' => the WHERE part of the SQL statement with named or unnamed parameters
     *      'params' => single value or array of values as paramter for the query the array
     *                  MUST be associative if named parameters are used
     *      'group' => single string or array of strings for the GROUP BY part
     *      'having' => the HAVING part of the SQL statement
     *      'havingParams' => single value or array of values as parameter for the query
     *                        the array MUST be associative if named parameters are used
     *      'order' => single string or array of strings for the ORDER BY part
     *      'limit' => number of records to be returned
     *      'offset' => start point for the records
     *
     * @param array $queryParams
     * @return AbstractResultSet
     */
    public static function queryByArray($queryParams = [])
    {
        # Converts the params array to local variables but instead of default values
        # it's necessary to check them all and set the defaults
        extract($queryParams);
        $entity = (isset($entity)) ? $entity : '\StdClass';
        $fields = (isset($fields)) ? $fields : null;
        $from = (isset($from)) ? static::tableName($from) : null;
        $join = (isset($join)) ? $join : [];
        $joins = (is_object($join)) ? [$join] : $join;
        $query = (isset($query)) ? $query : null;
        $params = (isset($params)) ? $params : [];
        $params = (!is_array($params)) ? [$params] : $params;
        $group = (isset($group)) ? $group : [];
        $having = (isset($having)) ? $having : [];
        $havingParams = (isset($havingParams)) ? $havingParams : [];
        $havingParams = (!is_array($havingParams)) ? [$havingParams] : $havingParams;
        $order = (isset($order)) ? $order : [];
        $limit = (isset($limit)) ? $limit : null;
        $offset = (isset($offset)) ? $offset : null;
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->select(static::tableName($entity), $fields, $from, $joins, $query, $group, $having, $order, $limit, $offset);
        return static::nativeQuery($entity, $sql, array_merge($params, $havingParams));
    }

    /**
     * Builds an object for a table join
     *
     * @param string $type one of the constants [EQM::INNER_JOIN|EQM::LEFT_JOIN|EQM::RIGHT_JOIN]
     * @param object|string $toObjectOrClass object or class name optional with an alias
     * @param string $query the query that combines two table with a join
     * @return object
     */
    public static function join($type, $toObjectOrClass, $query)
    {
        return (object)['type' => $type, 'to' => static::tableName($toObjectOrClass), 'query' => $query];
    }

    /**
     * Return the table name for a class name including the alias the assigned covention
     * handler is used to get the table name
     *
     * @param object|string $objectOrClass [<Classname>|<Classname> <alias>|object]
     * @return string table name blank alias name
     */
    protected static function tableName($objectOrClass)
    {
        $class = (is_object($objectOrClass)) ? get_class($objectOrClass) : $objectOrClass;
        $classParts = explode(' ', $class);
        $alias = null;
        if (count($classParts) > 1) {
            $class = $classParts[0];
            $alias = $classParts[1];
        }
        $tmp = explode('\\', $class);
        $className = end($tmp);
        $table = null;
        if (class_exists($class) && property_exists($objectOrClass, '__table'))
            $table = ObjectProperty::get('__table', $objectOrClass);
        $result = static::$conventionHandler[static::$activeConnection]->tableName($className, $table);
        if ($alias) {
            $tableParts = explode(' ', $result);
            $result = $tableParts[0] . ' ' . $alias;
        }
        return $result;
    }

    /**
     * handles event methods like preInsert() postUpdate() and so on
     *
     * @param string $event
     * @param object $object
     * @return object
     */
    protected static function handleEvent($event, $object)
    {
        if (method_exists($object, $event)) $object->{$event}();
    }

    /**
     * returns the table meta information as an object that implements TableMetaInterface
     * all read information will be cached in a metaCache variable but only for the single request
     *
     * @param object|string $objectOrClass
     * @return TableMetaInterface
     */
    public static function tableMeta($objectOrClass)
    {
        $table = static::tableName($objectOrClass);
        if (!array_key_exists($table, static::$metaCache[static::$activeConnection])) {
            $tableMeta = static::$sqlBuilder[static::$activeConnection]->tableMeta(
                $table,
                static::$db[static::$activeConnection]
            );
            static::$metaCache[static::$activeConnection][$table] = $tableMeta;
        }
        return static::$metaCache[static::$activeConnection][$table];
    }
}