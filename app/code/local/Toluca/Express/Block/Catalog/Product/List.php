<?php
/**
 * @package     Toluca_Express
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Product list
 */
class Toluca_Express_Block_Catalog_Product_List
    extends Mage_Catalog_Block_Product_List
{
    /**
     * Retrieve Toolbar block
     *
     * @return Mage_Catalog_Block_Product_List_Toolbar|Mage_Core_Block_Abstract
     */
    public function getToolbarBlock()
    {
        $block = parent::getToolbarBlock();

        $block->setData('_current_grid_mode', 'grid');
        $block->setData('_current_limit', 'all');

        return $block;
    }

    /**
     * Retrieve list toolbar HTML
     *
     * @return null
     */
    public function getToolbarHtml()
    {
        return null;
    }
}

