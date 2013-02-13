<?php

namespace Scandio\ORM;

class PrefixedConventionHandler implements ConventionHandlerInterface
{
    /**
     * @var string the prefix
     */
    protected $prefix = '';

    /**
     * @var ConventionHandlerInterface any convention handler
     */
    protected $conventionHandler;

    /**
     * @param string $prefix any string which is allowed for database table names
     * @param ConventionHandlerInterface $conventionHandler any convention handler object
     */
    public function __construct($prefix, ConventionHandlerInterface $conventionHandler)
    {
        if (is_string($prefix)) $this->prefix = $prefix;
        $this->conventionHandler = $conventionHandler;
    }

    /**
     * @param string $className the class name
     * @param string $tableName optional the table name
     * @return string MUST return a tuple of table name blank class name
     */
    public function tableName($className, $tableName = null)
    {
        return $this->prefix . $this->conventionHandler->tableName($className, $tableName);
    }
}