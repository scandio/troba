<?php

namespace troba\EQM;

/**
 * Default naming conventions for troba\EQM
 */
class DefaultConventionHandler implements ConventionHandlerInterface
{
    /**
     * @param string$className the name of a corresponding class
     * @param string $tableName optional the real table name
     *
     * @return string MUST return a tuple table name blank alias name
     */
    public function tableName($className, $tableName = null)
    {
        return (($tableName) ? $tableName : $className) . ' ' . $className;
    }
}