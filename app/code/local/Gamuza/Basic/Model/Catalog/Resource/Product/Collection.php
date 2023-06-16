<?php
/*
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Product collection
 */
class Gamuza_Basic_Model_Catalog_Resource_Product_Collection
    extends Mage_Catalog_Model_Resource_Product_Collection
{
    public function addWebsiteNamesToResult()
    {
        // nothing
    }

    public function joinField ($alias, $table, $field, $bind, $cond = null, $joinType = 'inner')
    {
        if (!strcmp ($alias, 'qty') && Mage::helper ('catalog')->isModuleEnabled ('Mage_CatalogInventory'))
        {
            $this->joinField(
                'is_in_stock',
                'cataloginventory/stock_item',
                'is_in_stock',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left'
            );
        }

        return parent::joinField ($alias, $table, $field, $bind, $cond, $joinType);
    }

    public function _addWebsiteNamesToResult()
    {
        return Mage_Catalog_Model_Resource_Product_Collection::addWebsiteNamesToResult();
    }
}

