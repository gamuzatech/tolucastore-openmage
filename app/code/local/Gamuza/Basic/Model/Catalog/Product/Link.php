<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Catalog product link model
 */
class Gamuza_Basic_Model_Catalog_Product_Link extends Mage_Catalog_Model_Product_Link
{
    public const LINK_TYPE_MATERIAL = 6;

    /**
     * @return $this
     */
    public function useMaterialLinks()
    {
        $this->setLinkTypeId(self::LINK_TYPE_MATERIAL);

        return $this;
    }

    /**
     * Save data for product relations
     *
     * @param   Mage_Catalog_Model_Product $product
     * @return  Mage_Catalog_Model_Product_Link
     */
    public function saveProductRelations($product)
    {
        $result = parent::saveProductRelations($product);

        $data = $product->getMaterialLinkData();

        if (!is_null($data))
        {
            $this->_getResource()->saveProductLinks($product, $data, self::LINK_TYPE_MATERIAL);
        }

        return $result;
    }
}

