<?php

namespace Scandio\ORM;

class ColumnMeta
{
    /**
     * @var string column name
     */
    public $name;

    /**
     * @var string default value in database
     */
    public $default;

    /**
     * @var string YES|NO
     */
    public $nullable;

    /**
     * @var string varchar, int ....
     */
    public $dataType;

    /**
     * @var string the full type definition from the database
     */
    public $columnType;

    /**
     * @var string PRI if primary
     */
    public $key;

    /**
     * @var string auto_increment if column can count ;-)
     */
    public $extra;

    /**
     * @param string $name
     * @param string $default
     * @param string $nullable
     * @param string $dataType
     * @param string $columnType
     * @param string $key
     * @param string $extra
     */
    public function __construct($name, $default, $nullable, $dataType, $columnType, $key, $extra)
    {
        $this->name = $name;
        $this->default = $default;
        $this->nullable = $nullable;
        $this->dataType = $dataType;
        $this->columnType = $columnType;
        $this->key = $key;
        $this->extra = $extra;
    }

    /**
     * @return bool
     */
    public function isPrimary()
    {
        return ($this->key == 'PRI');
    }

    /**
     * @return bool
     */
    public function isAutoIncrement()
    {
        return ($this->extra == 'auto_increment');
    }
}