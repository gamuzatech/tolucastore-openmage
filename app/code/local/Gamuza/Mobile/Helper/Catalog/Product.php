<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Catalog category helper
 */
class Gamuza_Mobile_Helper_Catalog_Product extends Mage_Catalog_Helper_Product
{
    /**
     * Return loaded product instance
     *
     * @param  int|string $productId (SKU or ID)
     * @param  int $store
     * @param  string $identifierType
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct($productId, $store, $identifierType = null)
    {
        /** @var $product Mage_Catalog_Model_Product */
        $product = Mage::getModel('catalog/product')->setStoreId(Mage::app()->getStore($store)->getId());

        $expectedIdType = false;

        if ($identifierType === null)
        {
            if (is_string($productId) && !preg_match("/^[+-]?[1-9][0-9]*$|^0$/", $productId))
            {
                $expectedIdType = 'sku';
            }
        }

        if ($identifierType == 'sku' || $expectedIdType == 'sku')
        {
            $idBySku = $product->getIdBySku($productId);

            if ($idBySku)
            {
                $productId = $idBySku;
            }
            else if ($identifierType == 'sku')
            {
                // Return empty product because it was not found by originally specified SKU identifier
                return $product;
            }
        }

        if ($productId && is_numeric($productId))
        {
            $product->load((int) $productId);
        }

        if (!$product || !$product->getId())
        {
            $productAttributes = array(
                'sku', 'name', 'weight', 'price', 'special_price',
            );

            $product = $product->loadByAttribute($identifierType, $productId, $productAttributes);
        }

        // stock data
        $product->setStockItem(Mage::getModel('cataloginventory/stock_item')->loadByProduct($product));

        return $product;
    }
}

