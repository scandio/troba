<?php

namespace troba\Model;

use troba\EQM\EQM;

trait Queries
{
    /**
     * @return \troba\EQM\Query query object of the called class
     */
    public static function query()
    {
        return EQM::query(get_called_class());
    }
}