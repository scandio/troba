<?php

namespace troba\Model;

trait Queries
{
    /**
     * @return \troba\EQM\Query query object of the called class
     */
    public static function query()
    {
        return \troba\EQM\EQM::query(get_called_class());
    }
}