<?php

namespace troba\EQM;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

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
     * Run mode dev (for development)
     */
    const DEV_MODE = 'dev';

    /**
     * Run mode prod (for production)
     */
    const PROD_MODE = 'prod';

    /**
     * Psr-3 logger object
     */
    const LOGGER = 'logger';

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
     * @var null|LoggerInterface Psr-3 logger object
     */
    protected static $logger = null;

    /**
     * @static
     *
     * @param array $config PDO configuration [dsn, user, password, run_mode, result_Set_class (class name)]
     * @param string $connectionName optional connection name
     *
     * @throws EQMException
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
            if (array_key_exists(self::LOGGER, $config) && $config[self::LOGGER] instanceof LoggerInterface) {
                static::$logger = $config[self::LOGGER];
            } else {
                static::$logger = new NullLogger();
            }
        } catch (\PDOException $e) {
            throw new EQMException($e->getMessage(), $e->getCode(), $e);
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
     * @throws EQMException
     * @return bool|int return a boolean or the last inserted id
     */
    public static function nativeExecute($sql, $params = [], $lastInsertedId = false)
    {
        if (!is_array($params)) $params = [$params];
        static::$logger->info($sql, $params);
        try {
            $stmt = static::$db[static::$activeConnection]->prepare($sql);
            $stmt->execute($params);
            return (($lastInsertedId) ? static::$db[static::$activeConnection]->lastInsertId() : $stmt->rowCount());
        } catch (\PDOException $e) {
            static::$logger->error($e->getMessage());
            throw new EQMException($e->getMessage(), $e->getCode(), $e);
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
     * @throws EQMException
     * @return AbstractResultSet set of objects - the result class is defined in $objectOrClass
     */
    public static function nativeQuery($objectOrClass, $sql = null, $params = [])
    {
        $objectOrClass = (is_object($objectOrClass)) ? get_class($objectOrClass) : $objectOrClass;
        if (!is_array($params)) $params = [$params];
        static::$logger->info($sql, $params);
        try {
            $stmt = static::$db[static::$activeConnection]->prepare($sql);
            $stmt->execute($params);
            return new ResultSet($stmt, $objectOrClass);
        } catch (\PDOException $e) {
            throw new EQMException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
