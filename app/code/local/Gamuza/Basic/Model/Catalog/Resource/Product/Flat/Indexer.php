<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Catalog Product Flat Indexer Resource Model
 */
class Gamuza_Basic_Model_Catalog_Resource_Product_Flat_Indexer
    extends Mage_Catalog_Model_Resource_Product_Flat_Indexer
{
    /**
     * Add or Update static attributes
     *
     * @param int $storeId
     * @param int|array $productIds update only product(s)
     * @return Mage_Catalog_Model_Resource_Product_Flat_Indexer
     */
    public function updateStaticAttributes ($storeId, $productIds = null)
    {
        parent::updateStaticAttributes ($storeId, $productIds);

        $resource = Mage::getSingleton ('core/resource');

        $read  = $resource->getConnection ('core_read');
        $write = $resource->getConnection ('core_write');

        $table = $this->getFlatTableName ($storeId);

        $query = sprintf ('SELECT entity_id FROM %s', $table);

        $ids = $read->fetchAll ($query);

        for ($i = 0; $i < count ($ids); $i ++)
        {
            $query = sprintf ('UPDATE catalog_product_entity SET sku_position = %s WHERE entity_id = %s LIMIT 1', ($i + 1), $ids [$i]['entity_id']);

            $write->query ($query);
        }
/*
        for ($i = 0; $i < count ($ids); $i ++)
        {
            $query = sprintf ('UPDATE %s SET sku_position = %s WHERE entity_id = %s LIMIT 1', $table, ($i + 1), $ids [$i]['entity_id']);

            $write->query ($query);
        }
*/
        return $this;
    }

    /**
     * Update non static EAV attributes flat data
     *
     * @param int $storeId
     * @param int|array $productIds update only product(s)
     * @return Mage_Catalog_Model_Resource_Product_Flat_Indexer
     */
    public function updateEavAttributes ($storeId, $productIds = null)
    {
        if (!$this->_isFlatTableExists ($storeId))
        {
            return $this;
        }

        foreach ($this->getAttributes () as $attribute)
        {
            /* @var $attribute Mage_Eav_Model_Entity_Attribute */
            if ($attribute->getBackend ()->getType () != 'static')
            {
                $this->updateAttribute ($attribute, $storeId, $productIds);
            }
            else if ($attribute->getAttributeCode () == Gamuza_Basic_Helper_Data::PRODUCT_ATTRIBUTE_SKU_POSITION)
            {
                $this->updateAttribute ($attribute, $storeId, $productIds);
            }
        }

        return $this;
    }
}

