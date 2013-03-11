<?php

namespace troba\Model;

/**
 * use this trait if you entity class should have finders
 */
trait Finders
{
    use Queries;

    /**
     * find an object by it primary key(s)
     *
     * @param array|mixed $primaries a single value or an assoc array
     * @return object the requested entity
     */
    public static function find($primaries)
    {
        return \troba\EQM\EQM::queryByPrimary(get_called_class(), $primaries);
    }

    /**
     * @param array|string $order optional the ORDER BY part of a query
     * @param int $limit optional number of records to be returned
     * @param int $offset optional the offset
     * @return \troba\EQM\AbstractResultSet
     */
    public static function findAll($order = [], $limit = null, $offset = null)
    {
        return static::query()->orderBy($order)->result($limit, $offset);
    }

    /**
     * find by a single property
     *
     * TODO implement multiple properties as optional feature
     * @param string $property
     * @param mixed $value
     * @param array|string $order optional
     * @param int $limit optional
     * @param int $offset optional
     * @return \troba\EQM\AbstractResultSet
     */
    public static function findBy($property, $value, $order = [], $limit = null, $offset = null)
    {
        return static::query()->where($property . ' = ?', $value)->orderBy($order)->result($limit, $offset);
    }

    /**
     * calling a method like findBy<property>() e.g. findByName()
     *
     * @param string $name
     * @param array $args [ params, order, limit, offset ]
     * @return \troba\EQM\AbstractResultSet
     */
    public static function __callStatic($name, $args)
    {
        $parts = explode('findBy', $name);
        if (count($parts) == 2 && empty($parts[0])) {
            return static::findBy(
                lcfirst($parts[1]),
                $args[0],
                (isset($args[1])) ? $args[1] : null,
                (isset($args[2])) ? $args[2] : null,
                (isset($args[3])) ? $args[2] : null
            );
        }
        return null;
    }
}
