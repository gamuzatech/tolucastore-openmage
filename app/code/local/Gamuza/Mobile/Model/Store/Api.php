<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Mobile_Model_Store_Api extends Mage_Api_Model_Resource_Abstract
{
    use Gamuza_Mobile_Trait_Api_Resource;

    /**
     * Retrieve list of stores
     *
     * @param null|object|array $filters
     * @return array
     */
    public function items ($filters = null)
    {
        return $this->_getStoreList ($filters);
    }
}

