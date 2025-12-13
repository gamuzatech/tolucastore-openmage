<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * PDV Desktop API
 */
class Toluca_PDV_Model_Desktop_Api extends Mage_Api_Model_Resource_Abstract
{
    public function config ($path)
    {
        return Mage::helper ('pdv')->getStoreConfig ($path);
    }
}

