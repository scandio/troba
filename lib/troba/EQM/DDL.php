<?php

namespace troba\EQM;

/**
 * an EQM with additional methods for database definition and migrations
 */
class DDL extends EQM
{
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
     * @param object|string $entity
     * @param array $columns
     * @return bool|int
     */
    public static function create($entity, $columns = [])
    {
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->create(static::tableName($entity), $columns);
        return static::nativeExecute($sql);
    }

    /**
     * @param object|string $entity
     * @param string $column
     * @param array $definition
     * @return bool|int
     */
    public static function addColumn($entity, $column, $definition)
    {
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->addColumn(static::tableName($entity), $column, $definition);
        return static::nativeExecute($sql);
    }

    /**
     * @param object|string $entity
     * @param string $column
     * @return bool|int
     */
    public static function removeColumn($entity, $column)
    {
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->dropColumn(static::tableName($entity), $column);
        return static::nativeExecute($sql);
    }

    /**
     * @param object|string $entity
     * @param string $column
     * @param string $newProperty
     * @param array $definition
     * @return bool|int
     */
    public static function changeColumn($entity, $column, $newProperty, $definition)
    {
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->changeColumn(static::tableName($entity), $column, $newProperty, $definition);
        return static::nativeExecute($sql);
    }

    /**
     * @param object|string $fromEntity
     * @param object|string $toEntity
     * @param array $keys
     * @return bool|int
     */
    public static function addReference($fromEntity, $toEntity, $keys = [])
    {
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->addReference(static::tableName($fromEntity), static::tableName($toEntity), $keys);
        return static::nativeExecute($sql);
    }

    /**
     * @param object|string $fromEntity
     * @param object|string $toEntity
     * @return bool|int
     */
    public static function removeReference($fromEntity, $toEntity)
    {
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->removeReference(static::tableName($fromEntity), static::tableName($toEntity));
        return static::nativeExecute($sql);
    }

    /**
     * @param object|string $entity
     * @param array|string $columns
     * @param string $indexType optional [DDL::INDEX | DDL::UNIQUE | DDL::PRIMARY]
     * @return bool|int
     */
    public static function addIndex($entity, $columns, $indexType = DDL::INDEX)
    {
        $columns = (is_array($columns)) ? $columns : [$columns];
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->addIndex(static::tableName($entity), $columns, $indexType);
        return static::nativeExecute($sql);
    }

    /**
     * @param object|string $entity
     * @param array|string $columns
     * @param string $indexType optional [DDL::INDEX | DDL::UNIQUE | DDL::PRIMARY]
     * @return bool|int
     */
    public static function removeIndex($entity, $columns, $indexType = DDL::INDEX)
    {
        $columns = (is_array($columns)) ? $columns : [$columns];
        $sql = static::$sqlBuilder[static::$activeConnection]
            ->removeIndex(static::tableName($entity), $columns, $indexType);
        return static::nativeExecute($sql);
    }

    /**
     * @param object|string $entity
     * @return bool|int
     */
    public static function drop($entity)
    {
        $sql = static::$sqlBuilder[static::$activeConnection]->drop(static::tableName($entity));
        return static::nativeExecute($sql);
    }

    /**
     * @param object|string $entity
     * @param string $file
     * @param array $options optional
     */
    public static function generateModel($entity, $file, $options = [])
    {

    }

}
