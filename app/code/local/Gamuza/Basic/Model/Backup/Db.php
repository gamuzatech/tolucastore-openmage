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
    public const BUFFER_LENGTH = parent::BUFFER_LENGTH * 1024;
}

