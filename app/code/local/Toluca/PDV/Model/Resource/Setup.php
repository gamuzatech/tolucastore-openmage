<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Setup Model of PDV Module
 */
class Toluca_PDV_Model_Resource_Setup extends Mage_Sales_Model_Resource_Setup
{
    public function __construct ($resourceName)
    {
        parent::__construct ($resourceName);

        if (Mage::helper ('core')->isModuleEnabled ('Gamuza_Brazil'))
        {
            $this->_flatEntityTables ['nfce'] = 'brazil/nfce';
        }
    }
}

