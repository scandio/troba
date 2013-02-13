<?php

namespace troba\Model;

trait Persisters
{
    /**
     * inserts a record to the table
     *
     * @return bool|object if auto_increment
     */
    public function insert()
    {
        return \troba\EQM\EQM::insert($this);
    }

    /**
     * updates a record of the table
     *
     * @return bool
     */
    public function update()
    {
        return \troba\EQM\EQM::update($this);
    }

    /**
     * deletes a record in the table but leaves the object as it is
     *
     * @return bool
     */
    public function delete()
    {
        return \troba\EQM\EQM::delete($this);
    }

    /**
     * saves a record with a auto increment primary key which means if the key is null
     * it will be inserted and if a key exists it will be updated
     *
     * @return bool|object
     * @throws \troba\EQM\EQMException
     */
    public function save()
    {
        $tableMeta = \troba\EQM\EQM::tableMeta($this);
        if ($tableMeta->hasAutoIncrement()) {
            if (is_null($this->{$tableMeta->getAutoIncrement()})) {
                return $this->insert();
            } else {
                return $this->update();
            }
        } else {
            throw new \troba\EQM\EQMException('save() is not possible for entities without auto increment primary key', 10001);
        }
}

}