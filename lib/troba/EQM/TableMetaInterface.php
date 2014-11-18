<?php

namespace troba\EQM;

interface TableMetaInterface {

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
