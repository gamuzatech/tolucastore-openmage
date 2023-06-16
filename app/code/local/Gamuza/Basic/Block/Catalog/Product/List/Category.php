<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Catalog product random items block
 */
class Gamuza_Basic_Block_Catalog_Product_List_Category
    extends Mage_Catalog_Block_Product_List_Random
{
    /**
     * Retrieve Toolbar block
     *
     * @return Mage_Catalog_Block_Product_List_Toolbar
     */
    public function getToolbarBlock()
    {
        $block = parent::getToolbarBlock();

        $block->setData('_current_grid_mode', 'grid');
        $block->setData('_current_limit', '10');

        return $block;
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return null
     */
    public function getToolbarHtml ()
    {
        return null;
    }

    /**
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _getProductCollection ()
    {
        parent::_getProductCollection ();

        $this->_productCollection->addCategoryFilter ($this->getCategory ());
        $this->_productCollection->setPageSize (10);

        return $this->_productCollection;
    }
}

