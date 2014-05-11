<?php

namespace troba\Model;

use troba\EQM\EQM;
use troba\EQM\EQMException;

trait Persisters
{
    /**
     * inserts a record to the table
     *
     * @return bool|object if auto_increment
     */
    public function insert()
    {
        return EQM::insert($this);
    }

    /**
     * updates a record of the table
     *
     * @return bool
     */
    public function update()
    {
        return EQM::update($this);
    }

    /**
     * deletes a record in the table but leaves the object as it is
     *
     * @return bool
     */
    public function delete()
    {
        return EQM::delete($this);
    }

    /**
     * saves a record with a auto increment primary key which means if the key is null
     * it will be inserted and if a key exists it will be updated
     *
     * @return bool|object
     * @throws EQMException
     */
    public function save()
    {
        $tableMeta = EQM::tableMeta($this);
        if ($tableMeta->hasAutoIncrement()) {
            if (empty($this->{$tableMeta->getAutoIncrement()})) {
                return $this->insert();
            } else {
                return $this->update();
            }
        } else {
            throw new EQMException('save() is not possible for entities without auto increment primary key', 10001);
        }
}

}