<?php

namespace Scandio\ORM;

/**
 * Manage all PDO requests and settings
 */
class PDOWrapper
{
    /**
     * Run mode management
     */
    const RUN_MODE = 'run_mode';

    /**
     * ResultSet
     */
    const RESULT_SET_CLASS = 'result_set_class';

    /**
     * Run mode dev (for development)
     */
    const DEV_MODE = 'dev';

    /**
     * Run mode prod (for production)
     */
    const PROD_MODE = 'prod';

    /**
     * @var \PDO[] list of PDO connections
     */
    protected static $db = [];

    /**
     * @var string currently used connection
     */
    protected static $activeConnection = 'default';

    /**
     * @var string[] array of run modes per connection
     */
    protected static $runMode = [];

    /**
     * @var string[] array of class names for specific result sets
     */
    protected static $resultSetClass = [];

    /**
     * @static
     *
     * @param array $config PDO configuration [dsn, user, password, run_mode, result_Set_class (class name)]
     * @param string $connectionName optional connection name
     *
     * @throws ORMException
     */
    public static function initialize($config = [], $connectionName = 'default')
    {
        try {
            static::$db[$connectionName] = new \PDO(
                $config['dsn'],
                $config['username'],
                $config['password'],
                [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"]
            );
            static::$db[$connectionName]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            if (array_key_exists(self::RUN_MODE, $config)) {
                static::$runMode[$connectionName] = $config[self::RUN_MODE];
            } else {
                static::$runMode[$connectionName] = self::PROD_MODE;
            }
            if (array_key_exists(ORM::RESULT_SET_CLASS, $config)) {
                static::$resultSetClass[$connectionName] = $config[self::RESULT_SET_CLASS];
            } else {
                static::$resultSetClass[$connectionName] = '\\Scandio\\ORM\\ResultSet';
            }
        } catch (\PDOException $e) {
            throw new ORMException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Begin transaction for current connection
     *
     * @static
     */
    public static function begin()
    {
        static::$db[static::$activeConnection]->beginTransaction();
    }

    /**
     * Commit transaction for current connection
     *
     * @static
     */
    public static function commit()
    {
        static::$db[static::$activeConnection]->commit();
    }

    /**
     * Roll back transaction for current connection
     *
     * @static
     */
    public static function rollBack()
    {
        static::$db[static::$activeConnection]->rollBack();
    }

    /**
     * Activates a connection which was already initiated with initialize().
     * Call activateConnection without a parameter to reset to default connection
     *
     * @static
     *
     * @param string $connectionName optional the name of the connection
     */
    public static function activateConnection($connectionName = null)
    {
        if (!is_null($connectionName) && array_key_exists($connectionName, static::$db)) {
            static::$activeConnection = $connectionName;
        } else {
            static::$activeConnection = 'default';
        }
    }

    /**
     * Call a sql statement with parameters. you can use ? or :param for parameters
     *
     * @static
     *
     * @param string $sql a valid sql statement for execution
     * @param array $params optional if the statement needs parameter
     * @param bool $lastInsertedId optional perform lastInsertedId after execution
     *
     * @return bool|int return a boolean or the last inserted id
     * @throws ORMException
     */
    public static function nativeExecute($sql, $params = [], $lastInsertedId = false)
    {
        if (!is_array($params)) $params = [$params];
        static::log($sql, $params);
        try {
            $stmt = static::$db[static::$activeConnection]->prepare($sql);
            $stmt->execute($params);
            return (($lastInsertedId) ? static::$db[static::$activeConnection]->lastInsertId() : $stmt->rowCount());
        } catch (\PDOException $e) {
            static::log($e->getMessage(), $e);
            throw new ORMException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Run a sql query and return the result in a list of objects
     * set first parameter to null if you want an assoc result
     *
     * @static
     *
     * @param object|string $objectOrClass object or class name for the result
     * @param string $sql a valid sql statement that returns results
     * @param array $params optional if the statement needs parameters
     *
     * @return ResultSetInterface set of objects - the result class is defined in $objectOrClass
     * @throws ORMException
     */
    public static function nativeQuery($objectOrClass, $sql = null, $params = [])
    {
        $objectOrClass = (is_object($objectOrClass)) ? get_class($objectOrClass) : $objectOrClass;
        if (!is_array($params)) $params = [$params];
        static::log($sql, $params);
        try {
            $stmt = static::$db[static::$activeConnection]->prepare($sql);
            $stmt->execute($params);
            return new static::$resultSetClass[static::$activeConnection]($stmt, $objectOrClass);
        } catch (\PDOException $e) {
            throw new ORMException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Logs all sql statements to the standard error log if the DEV_MODE ist set
     * @static
     *
     * @param string $sql the statement that should be logged
     * @param mixed $params optional the corresponding parameters
     */
    public static function log($sql, $params = null)
    {
        if (static::$runMode[static::$activeConnection] == ORM::DEV_MODE) {
            $paramString = '';
            if (!is_null($params)) {
                ob_start();
                var_dump($params);
                $paramString = ob_get_contents();
                ob_end_clean();
            }
            error_log(PHP_EOL . 'ORM: ' . $sql . PHP_EOL .
                    'with: ' . $paramString . PHP_EOL .
                    'using: ' . static::$activeConnection . PHP_EOL
            );
        }
    }
}
