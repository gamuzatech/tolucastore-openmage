<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Database backup model
 */
class Gamuza_Basic_Model_Backup_Db extends Mage_Backup_Model_Db
{
    /**
     * Buffer length for multi rows
     * default 100 Kb
     *
     */
    public const BUFFER_LENGTH = parent::BUFFER_LENGTH * parent::BUFFER_LENGTH;

    public function createBackup (Mage_Backup_Model_Backup $backup)
    {
        $backup->open (true);
        $backup->close ();
        $backup->deleteFile ();

        $filename = $backup->getPath () . DS . $backup->getFileName ();

        try
        {
            $backup->_write ($this->getResource ()->getHeader (), $filename);

            $tables = $this->getResource ()->getTables ();

            $ignoreDataTablesList = $this->getIgnoreDataTablesList ();

            foreach ($tables as $tableName)
            {
                /*
                $backup->_write ($this->getResource ()->getTableHeader ($tableName), $filename);
                */

                $tableStatus = $this->getResource ()->getTableStatus ($tableName, $tables);

                /*
                if ($tableStatus->getRows () && !in_array ($tableName, $ignoreDataTablesList))
                */
                {
                    /*
                    $backup->_write ($this->getResource ()->getTableDataBeforeSql ($tableName), $filename);
                    */

                    $backup->_dump ($tableName, $filename);

                    /*
                    $backup->_write ($this->getResource ()->getTableDataAfterSql ($tableName), $filename);
                    */
                }
            }

            /*
            $backup->_write ($this->getResource ()->getTableForeignKeysSql (), $filename);
            */
            $backup->_write ($this->getResource ()->getFooter (), $filename);

            clearstatcache (true, $filename);
        }
        catch (Exception $e)
        {
            throw $e;
        }

        return $this;
    }
}

