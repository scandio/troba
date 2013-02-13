<?php

namespace Scandio\ORM;

interface TableMetaInterface
{
    const STRING = 'string';
    const INT = 'int';
    const FLOAT = 'float';
    const DECIMAL = 'decimal';
    const BOOLEAN = 'boolean';
    const DATE_TIME = 'date_time';

    /**
     * @param \StdClass[] $data
     */
    public function __construct($data);

    /**
     * @return ColumnMeta[]
     */
    public function getColumns();

    /**
     * @param string $column
     * @return ColumnMeta
     */
    public function getColumnMeta($column);

    /**
     * @return array
     */
    public function getPrimary();

    /**
     * @return string
     */
    public function getAutoIncrement();

    /**
     * @return bool
     */
    public function hasAutoIncrement();
}
