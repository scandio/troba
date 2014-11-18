<?php

namespace troba\EQM;

/**
 * Default naming conventions for troba\EQM
 */
class DefaultConventionHandler implements ConventionHandlerInterface {

    /**
     * Returns the table name for the given class and adds the class name as an alias
     *
     * @param string $className the name of a corresponding class
     * @param string $tableName optional the real table name
     *
     * @return string MUST return a tuple table name blank alias name
     */
    public function tableName($className, $tableName = null) {
        return (($tableName) ? $tableName : $className) . ' ' . $className;
    }
}