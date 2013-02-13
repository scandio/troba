<?php

namespace Scandio\ORM;

class SqliteBuilder extends MySqlBuilder
{
    public function tableMeta($table, $pdo)
    {
        $stmt = $pdo->prepare("PRAGMA table_info('{$this->tableNameDef($table)->table}');");
        $stmt->execute();
        $columns = $stmt->fetchAll(\PDO::FETCH_CLASS, 'StdClass');
        return new SqliteTableMeta($columns);
    }
}
