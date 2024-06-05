<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Backend model for cities CSV importing
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Backend_City extends Mage_Core_Model_Config_Data
{
    public function _afterSave ()
    {
        Mage::getResourceModel ('brazil/city')->uploadAndImport ($this);

        return $this;
    }
}

