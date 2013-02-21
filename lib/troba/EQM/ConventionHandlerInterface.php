<?php

namespace troba\EQM;

/**
 * Naming Convention Interface
 */
interface ConventionHandlerInterface
{
    /**
     * Returns the table name for the given class and adds the class name as an alias
     *
     * @abstract
     *
     * @param string $className the name of a corresponding class
     * @param string $tableName optional the real table name
     *
     * @return string MUST return a tuple table name blank alias name
     */
    public function tableName($className, $tableName = null);
}
