<?php

namespace troba\EQM;

/**
 * Table meta information for MySQL tables
 */
class MySqlTableMeta implements TableMetaInterface
{
    /**
     * @var ColumnMeta[]
     */
    protected $columns = [];

    /**
     * @var array
     */
    protected $primaries = [];

    /**
     * @var null|string
     */
    protected $autoIncrement = null;

    /**
     * @param \StdClass[] $columns
     */
    public function __construct($columns)
    {
        foreach ($columns as $column) {
            $this->columns[$column->column_name] = new ColumnMeta($column->column_name, $column->column_default,
                $column->is_nullable, $column->data_type, $column->column_type, $column->column_key, $column->extra);
            if ($column->column_key == 'PRI') $this->primaries[$column->column_name] = $column->column_name;
            if ($column->extra == 'auto_increment') $this->autoIncrement = $column->column_name;
        }
    }

    /**
     * @return ColumnMeta[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param string $column
     * @return null|ColumnMeta
     */
    public function getColumnMeta($column)
    {
        return array_key_exists($column, $this->columns) ? $this->columns[$column] : null;
    }

    /**
     * @return array
     */
    public function getPrimary()
    {
        return $this->primaries;
    }

    /**
     * @return null|string
     */
    public function getAutoIncrement()
    {
        return $this->autoIncrement;
    }

    /**
     * @return bool
     */
    public function  hasAutoIncrement()
    {
        return (!is_null($this->autoIncrement));
    }
}

