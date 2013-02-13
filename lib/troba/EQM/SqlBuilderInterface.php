<?php

namespace troba\EQM;

/**
 * All SQL relevant statement generation as one "driver"
 */
interface SqlBuilderInterface
{
    /**
     * Generates a sql statement for inserting records
     *
     * @abstract
     *
     * @param string $table table name <table> [<alias>]
     * @param array $fields the columns for insertion only the keys will be used
     *
     * @return string an insert sql statement
     */
    public function insert($table, $fields = []);

    /**
     * Generates a sql statement for updating records
     *
     * @abstract
     *
     * @param string $table table name <table> [<alias>]
     * @param string $query the where part of a sql statement without where
     * @param array $fields the columns to be updated only the keys will be used
     *
     * @return string an insert sql statement
     */
    public function update($table, $query, $fields = []);

    /**
     * Generates a sql statement for deleting records
     *
     * @abstract
     *
     * @param string $table table name <table> [<alias>]
     * @param string $query optional the where part of a sql statement without where
     *
     * @return string an insert sql statement
     */
    public function delete($table, $query = null);

    /**
     * Generates a sql statement for selection records
     *
     * @abstract
     *
     * @param string $table table name <table> [<alias>]
     * @param string $fields requested fields comma separated
     * @param string $from the FROM part if not set through the table
     * @param array $joins optional array of join definitions
     * @param string $query optional the WHERE part of a sql statement without where
     * @param string|array $group optional GROUP BY part(s) of the sql statement
     * @param string $having optional the HAVING part of a sql statement without where
     * @param string|array $order optional ORDER BY part(s) of the sql statement
     * @param int $limit optional number of records to be returned
     * @param int $offset optional the offset of the records to be returned
     *
     * @return string an insert sql statement
     */
    public function select($table, $fields, $from = null, $joins = [], $query = null, $group = [],
                           $having = null, $order = [], $limit = null, $offset = null);

    /**
     * @param string $table
     * @return string
     */
    public function drop($table);

    /**
     * @param string $table
     * @param array $columns
     * @return string
     */
    public function create($table, $columns = []);

    /**
     * @param string $table
     * @param string $column
     * @param array|string $definition
     * @return string
     */
    public function addColumn($table, $column, $definition);

    /**
     * @param string $table
     * @param array|string $columns
     * @param string $indexType
     * @return string
     */
    public function addIndex($table, $columns, $indexType = 'index');

    /**
     * @param string $table
     * @param array|string $columns
     * @param string $indexType
     * @return string
     */
    public function removeIndex($table, $columns, $indexType = 'index');

    /**
     * @param string $fromTable
     * @param string $toTable
     * @param array $keys
     * @return string
     */
    public function addReference($fromTable, $toTable, $keys);

    /**
     * @param string $fromTable
     * @param string $toTable
     * @return string
     */
    public function removeReference($fromTable, $toTable);

    /**
     * @param string $table
     * @param string $column
     * @param string $newColumn
     * @param array|string $definition
     * @return string
     */
    public function changeColumn($table, $column, $newColumn, $definition);

    /**
     * @param string $table
     * @param string $column
     * @return string
     */
    public function dropColumn($table, $column);

    /**
     * @param string $table table name
     * @param \PDO $pdo
     * @return TableMetaInterface
     */
    public function tableMeta($table, $pdo);
}
