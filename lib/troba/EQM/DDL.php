<?php

namespace troba\EQM;

/**
 * an EQM with additional methods for database definition and migrations
 */
class DDL extends EQM {

    const INTEGER = 'integer';
    const STRING = 'string';
    const TEXT = 'text';
    const FLOAT = 'float';
    const DATE_TIME = 'date_time';

    const AUTO_INCREMENT = 'auto_increment';
    const PRIMARY = 'primary';
    const NOT_NULL = 'not_null';
    const UNIQUE = 'unique';
    const INDEX = 'index';

    /**
     * @param string $className
     * @param array $columns
     * @return bool|int
     */
    public static function create($className, $columns = []) {
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->create(static::tableName($className), $columns);
        return static::nativeExecute($sql);
    }

    /**
     * @param string $className
     * @param string $column
     * @param array $definition
     * @return bool|int
     */
    public static function addColumn($className, $column, $definition) {
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->addColumn(static::tableName($className), $column, $definition);
        return static::nativeExecute($sql);
    }

    /**
     * @param string $className
     * @param string $column
     * @return bool|int
     */
    public static function removeColumn($className, $column) {
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->dropColumn(static::tableName($className), $column);
        return static::nativeExecute($sql);
    }

    /**
     * @param string $className
     * @param string $column
     * @param string $newProperty
     * @param array $definition
     * @return bool|int
     */
    public static function changeColumn($className, $column, $newProperty, $definition) {
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->changeColumn(static::tableName($className), $column, $newProperty, $definition);
        return static::nativeExecute($sql);
    }

    /**
     * @param string $fromClassName
     * @param string $toClassName
     * @param array $keys
     * @return bool|int
     */
    public static function addReference($fromClassName, $toClassName, $keys = []) {
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->addReference(static::tableName($fromClassName), static::tableName($toClassName), $keys);
        return static::nativeExecute($sql);
    }

    /**
     * @param string $fromClassName
     * @param string $toClassName
     * @return bool|int
     */
    public static function removeReference($fromClassName, $toClassName) {
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->removeReference(static::tableName($fromClassName), static::tableName($toClassName));
        return static::nativeExecute($sql);
    }

    /**
     * @param string $className
     * @param array|string $columns
     * @param string $indexType optional [DDL::INDEX | DDL::UNIQUE | DDL::PRIMARY]
     * @return bool|int
     */
    public static function addIndex($className, $columns, $indexType = DDL::INDEX) {
        $columns = (is_array($columns)) ? $columns : [$columns];
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->addIndex(static::tableName($className), $columns, $indexType);
        return static::nativeExecute($sql);
    }

    /**
     * @param string $className
     * @param array|string $columns
     * @param string $indexType optional [DDL::INDEX | DDL::UNIQUE | DDL::PRIMARY]
     * @return bool|int
     */
    public static function removeIndex($className, $columns, $indexType = DDL::INDEX) {
        $columns = (is_array($columns)) ? $columns : [$columns];
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->removeIndex(static::tableName($className), $columns, $indexType);
        return static::nativeExecute($sql);
    }

    /**
     * @param string $className
     * @return bool|int
     */
    public static function drop($className) {
        $sql = static::$sqlBuilder[static::$activeConnection]->drop(static::tableName($className));
        return static::nativeExecute($sql);
    }

    /**
     * @param string $className
     * @param string $file
     * @param array $options optional
     */
    public static function generateModel($className, $file, $options = []) {

    }
}
