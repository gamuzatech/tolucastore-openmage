<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Database backup resource model
 */
class Gamuza_Basic_Model_Backup_Resource_Db extends Mage_Backup_Model_Resource_Db
{
    /**
     * Retrieve table status
     *
     * @param string $tableName
     * @return Varien_Object|false
     */
    public function getTableStatus($tableName)
    {
        $result = parent::getTableStatus($tableName);

        if (is_object($result) && !strcmp(php_sapi_name(), 'cli'))
        {
            echo sprintf(
                '%s: %s: %s (%s)',
                $result->getEngine(),
                $result->getName(),
                $result->getRows(),
                $result->getComment()
            ) . PHP_EOL;
        }

        return $result;
    }
}

