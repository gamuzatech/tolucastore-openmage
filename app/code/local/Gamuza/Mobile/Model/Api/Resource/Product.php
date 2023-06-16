<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Mobile_Model_Api_Resource_Product extends Mage_Checkout_Model_Api_Resource_Product
{
    use Gamuza_Mobile_Trait_Api_Resource;

    /**
     * Return loaded product instance
     *
     * @param  int|string $productId (SKU or ID)
     * @param  int|string $store
     * @param  string $identifierType
     * @return Mage_Catalog_Model_Product
     */
    protected function _getProduct($productId, $store = null, $identifierType = null)
    {
        $product = Mage::helper('mobile/catalog_product')->getProduct(
            $productId, $this->_getStoreId($store), $identifierType
        );

        return $product;
    }
}

