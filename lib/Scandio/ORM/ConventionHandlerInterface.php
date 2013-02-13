<?php

namespace Scandio\ORM;

/**
 * Naming Convention Interface
 */
interface ConventionHandlerInterface
{
    /**
     * @abstract
     *
     * @param string $className the name of a corresponding class
     * @param string $tableName optional the real table name
     *
     * @return string MUST a tuple table name blank alias name
     */
    public function tableName($className, $tableName = null);
}
