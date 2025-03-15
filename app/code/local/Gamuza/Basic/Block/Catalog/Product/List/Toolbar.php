<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Product list toolbar
 */
class Gamuza_Basic_Block_Catalog_Product_List_Toolbar
    extends Mage_Catalog_Block_Product_List_Toolbar
{
    /**
     * Init Toolbar
     *
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_direction = Mage::getStoreConfig(Gamuza_Basic_Model_Catalog_Config::XML_PATH_LIST_DEFAULT_DIRECTION);
    }
}

