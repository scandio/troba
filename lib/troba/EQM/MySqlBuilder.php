<?php

namespace troba\EQM;

/**
 * All SQL relevant statement generation for MySQL
 */
class MySqlBuilder implements SqlBuilderInterface
{

    /**
     * Creates a SQL statement for inserting fields as a record into a table
     *
     * @param string $table the table name with an alias
     * @param array $fields optional an associative array with the field names as key the values won't be used
     *
     * @return string the INSERT SQL statement with named parameters
     */
    public function insert($table, $fields = [])
    {
        $table = $this->tableNameDef($table);
        return "INSERT INTO {$table->table} (" . implode(', ', array_keys($fields)) .
            ") VALUES ( :" . implode(', :', array_keys($fields)) . ")";
    }

    /**
     * Creates a SQL statement for updating fields in a table
     *
     * @param string $table the table name with an alias
     * @param string $query the query that defines the record to be updated without WHERE
     * @param array $fields an associative array with the field names as key the values won't be used
     *
     * @return string the UPDATE SQL statement with named parameters
     */
    public function update($table, $query, $fields = [])
    {
        $table = $this->tableNameDef($table);
        $setFields = array_map(function ($value) {
            return $value . ' = :' . $value;
        }, array_keys($fields));
        return "UPDATE {$table->table} {$table->alias} SET " . implode(', ', $setFields) . " WHERE {$query}";
    }

    /**
     * Returns a SQL statment for deleting records in a table
     *
     * @param string $table the table name with an alias
     * @param string $query optional the query the defines the records to be deleted
     * @return string the DELETE SQL statement with parameters
     */
    public function delete($table, $query = null)
    {
        $table = $this->tableNameDef($table);
        return "DELETE FROM {$table->table}" . (!is_null($query) ? " WHERE {$query}" : "");
    }

    /**
     * Returns a SELECT SQL statement for the given parameters
     *
     * @param string $table
     * @param string $fields
     * @param string $from optional
     * @param array $joins optional array of joins
     * @param string $query otpional a where query
     * @param array|string $group optional a group string or an array if multiple
     * @param string $having optional a having query
     * @param array|string $order optional a order string or an array if multiple
     * @param int $limit optional the number of records to be returned
     * @param int $offset optional hte start position
     * @return string the SELECT SQL statement
     */
    public function select($table, $fields, $from = null, $joins = [], $query = null, $group = [],
                           $having = null, $order = [], $limit = null, $offset = null)
    {
        if (!is_null($from)) {
            $table = $this->tableNameDef($from);
        } else {
            $table = $this->tableNameDef($table);
        }
        $joinString = '';
        foreach ($joins as $join) {
            $to = $this->tableNameDef($join->to);
            $joinString .= " {$join->type} {$to->table} {$to->alias} ON {$join->query}";
        }
        $orderString = (!is_array($order)) ? $order : implode(', ', $order);
        $groupString = (!is_array($group)) ? $group : implode(', ', $group);
        $fields = (empty($fields)) ? "{$table->alias}.*" : $fields;
        $limiter = null;
        if (!is_null($offset)) $limiter .= $offset . ', ';
        if (!is_null($limit)) $limiter .= $limit;
        return "SELECT {$fields} " .
            "FROM {$table->table} {$table->alias}" .
            (!empty($joinString) ? $joinString : '') .
            (!empty($query) ? " WHERE {$query}" : "") .
            (!empty($groupString) ? " GROUP BY {$groupString}" : "") .
            (!empty($having) ? " HAVING {$having}" : "") .
            (!empty($orderString) ? " ORDER BY {$orderString}" : "") .
            (!is_null($limiter) ? " LIMIT {$limiter}" : "");
    }

    /**
     * Returns a SQL statement for dropping a table
     *
     * @param string $table
     * @return string DROP TABLE SQL statement
     */
    public function drop($table)
    {
        return "DROP TABLE {$this->tableNameDef($table)->table};";
    }

    /**
     * Returns a SQL statement for creating a table
     *
     * @param string $table
     * @param array $columns [<name>, [<column definition>]]
     * @return string CREATE TABLE SQL statement
     */
    public function create($table, $columns = [])
    {
        $columnString = '';
        $primaries = [];
        foreach ($columns as $column => $definition) {
            $columnString .= ((!empty($columnString)) ? ',' : '') .
                " {$column}{$this->getColumnDef($definition)}";
            if (in_array('primary', $definition)) {
                $primaries[] = $column;
            }
        }
        $primaryString = (count($primaries) > 0) ? ', PRIMARY KEY (' . implode(', ', $primaries) . ')' : '';
        return "CREATE TABLE {$this->tableNameDef($table)->table} ({$columnString}{$primaryString});";
    }

    /**
     * Returns a SQL statement for altering a table
     *
     * @param string $table
     * @param string $column
     * @param array|string $definition
     * @return string ALTER TABLE statement
     */
    public function addColumn($table, $column, $definition)
    {
        return "ALTER TABLE {$this->tableNameDef($table)->table} " .
            "ADD {$column}{$this->getColumnDef($definition)}";
    }

    /** Returns a SQL language part for different indexes
     * @param string $indexType
     * @return string the converted string for the SQL statement
     */
    protected function getIndexCommand($indexType)
    {
        if ($indexType == 'index') {
            $indexType = 'INDEX';
        } elseif ($indexType == 'unique') {
            $indexType = 'primary';
        } elseif ($indexType == 'primary') {
            $indexType = 'PRIMARY KEY';
        }
        return $indexType;
    }

    /**
     * Returns a SQL statement for adding an index to a table
     *
     * @param string $table
     * @param array $columns
     * @param string $indexType optional [index|unique|primary]
     * @return string ALTER TABLE SQL statement
     */
    public function addIndex($table, $columns, $indexType = 'index')
    {
        return "ALTER TABLE {$this->tableNameDef($table)->table} ADD {$this->getIndexCommand($indexType)} " .
            implode('_', $columns) . " (" .
            implode(', ', $columns) . ");";
    }

    /**
     * Returns a SQL statement for removing an index from a table
     *
     * @param string $table
     * @param array $columns
     * @param string $indexType optional [index|unique|primary]
     * @return string
     */
    public function removeIndex($table, $columns, $indexType = 'index')
    {
        return "ALTER TABLE {$this->tableNameDef($table)->table} DROP {$this->getIndexCommand($indexType)} " .
            (($indexType == 'primary') ? '' : implode('_', $columns));
    }

    /**
     * Returns a SQL statement for adding a reference between two tables
     *
     * @param string $fromTable
     * @param string $toTable
     * @param array $keys
     * @return string
     */
    public function addReference($fromTable, $toTable, $keys)
    {
        return "ALTER TABLE {$this->tableNameDef($fromTable)->table} " .
            "ADD CONSTRAINT {$this->tableNameDef($fromTable)->table}_{$this->tableNameDef($toTable)->table} " .
            "FOREIGN KEY ({$keys[0]}) REFERENCES {$this->tableNameDef($toTable)->table}({$keys[1]})";
    }

    /**
     * Returns a SQL statement to remove a reference between two tables
     *
     * @param string $fromTable
     * @param string $toTable
     * @return string
     */
    public function removeReference($fromTable, $toTable)
    {
        return "ALTER TABLE {$this->tableNameDef($fromTable)->table} " .
            "DROP FOREIGN KEY {$this->tableNameDef($fromTable)->table}_{$this->tableNameDef($toTable)->table};";
    }

    /**
     * Returns a SQL statement for changing or renaming an existing column in a table
     *
     * @param string $table
     * @param string $column
     * @param string $newColumn
     * @param array|string $definition
     * @return string
     */
    public function changeColumn($table, $column, $newColumn, $definition)
    {
        return "ALTER TABLE {$this->tableNameDef($table)->table} " .
            "CHANGE {$column} {$newColumn}{$this->getColumnDef($definition)}";
    }

    /**
     * Returns a SQL statement to remove a column in a table
     *
     * @param string $table
     * @param string $column
     * @return string
     */
    public function dropColumn($table, $column)
    {
        return "ALTER TABLE {$this->tableNameDef($table)->table} DROP {$column}";
    }

    /**
     * Converts the standard types to SQL type definitions
     *
     * @param array|string $definition
     * @return string
     */
    protected function getColumnDef($definition)
    {
        $definition = (is_array($definition)) ? $definition : [$definition];
        $result = '';
        if (count($definition) > 0) {
            $null = true;
            foreach ($definition as $element) {
                if ($element == 'string') {
                    $result .= ' varchar(255)';
                } elseif ($element == 'text') {
                    $result .= ' text';
                } elseif ($element == 'integer') {
                    $result .= ' int(11)';
                } elseif ($element == 'float') {
                    $result .= ' double';
                } elseif ($element == 'date_time') {
                    $result .= ' DATETIME';
                } elseif ($element == 'not_null') {
                    $result .= ' NOT NULL';
                    $null = false;
                } elseif ($element == 'unique') {
                    $result .= ' UNIQUE';
                } elseif ($element == 'auto_increment') {
                    $result .= ' AUTO_INCREMENT';
                } elseif (is_array($element) && key($element) == 'default') {
                    $result .= ' DEFAULT ' .
                        (is_string(current($element)) ? "'" . current($element) . "'" : current($element));
                }
            }
            $result .= ($null) ? ' NULL' : '';
        }
        return $result;
    }

    /**
     * Reads all meta information for a table and returns it as a object based in TableMetaInterface
     *
     * @param string $table
     * @param \PDO $pdo
     * @return MySqlTableMeta|TableMetaInterface
     */
    public function tableMeta($table, $pdo)
    {
        $stmt = $pdo->prepare("SELECT database()");
        $stmt->execute();
        $dbName = $stmt->fetch(\PDO::FETCH_ASSOC|\PDO::FETCH_PROPS_LATE)['database()'];
        $sql = "SELECT table_name, column_name, column_default, is_nullable, data_type, column_type, column_key, extra
                FROM information_schema.columns
                WHERE table_schema = '{$dbName}' AND table_name = '{$this->tableNameDef($table)->table}'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $columns = $stmt->fetchAll(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE, 'StdClass');
        return new MySqlTableMeta($columns);
    }

    /**
     * Split the table name into an object of table and alias
     *
     * @param string $table the table name with an alias
     * @return object an object with two attributes type and alias
     */
    protected function tableNameDef($table)
    {
        $parts = explode(' ', $table);
        $tableDefinition['table'] = $parts[0];
        $tableDefinition['alias'] = (count($parts) == 1) ? $tableDefinition['table'] : $parts[1];
        return (object)$tableDefinition;
    }
}