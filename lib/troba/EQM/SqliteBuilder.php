<?php

namespace troba\EQM;

class SqliteBuilder extends MySqlBuilder {

    /**
     * Reads the meta information for a table an returns it as an object
     * that implements TableMetaInterface
     *
     * @param string $table the table name
     * @param \PDO $pdo a valid PDO connection object
     * @return SqliteTableMeta
     */
    public function tableMeta($table, $pdo) {
        $stmt = $pdo->prepare("PRAGMA table_info('{$this->tableNameDef($table)->table}');");
        $stmt->execute();
        $columns = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, 'StdClass');
        return new SqliteTableMeta($columns);
    }
}
