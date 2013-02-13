<?php

namespace troba\EQM;

use troba\Util\StringUtils;

/**
 * Classic naming conventions for troba\EQM
 */
class ClassicConventionHandler implements ConventionHandlerInterface
{
    /**
     * Converts a camelCased class name to a underscored table name
     *
     * @param string $className the name of a corresponding class
     * @param string $tableName optional the real table name
     *
     * @return string MUST a tuple table name blank alias name
     */
    public function tableName($className, $tableName = null)
    {
        return ($tableName) ? $tableName . ' ' . $className :
            StringUtils::camelCaseTo($className, '_') . ' ' . $className;
    }
}
