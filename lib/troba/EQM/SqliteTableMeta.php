<?php

namespace troba\EQM;

class SqliteTableMeta extends MySqlTableMeta {

    /**
     * @param \StdClass[] $columns
     */
    public function __construct($columns) {
        foreach ($columns as $column) {
            $this->columns[$column->name] = new ColumnMeta($column->name, $column->dflt_value,
                $column->notnull, $column->type, $column->type, $column->pk,
                (($column->type == 'integer' && $column->pk == 1) ? 'auto_increment' : null));
            if ($column->pk == 1) $this->primaries[$column->name] = $column->name;
            if ($column->type == 'integer' && $column->pk == 1) $this->autoIncrement = $column->name;
        }
    }

}
