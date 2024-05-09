<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Backend model for ibpt CSV importing
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Backend_Ibpt extends Mage_Core_Model_Config_Data
{
    public function _afterSave ()
    {
        Mage::getResourceModel ('brazil/ibpt')->uploadAndImport ($this);

        return $this;
    }
}

