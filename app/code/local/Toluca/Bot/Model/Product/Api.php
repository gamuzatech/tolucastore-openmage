<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Product API
 */
class Toluca_Bot_Model_Product_Api extends Toluca_Bot_Model_Api_Resource_Abstract
{
    public function items ($categoryId, $categoryName = null)
    {
        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $collection = $this->_getCategoryCollection ($storeId)
            ->addFieldToFilter ('main_table.position', array ('eq' => $categoryId))
        ;

        $categoryId = $collection->getFirstItem ()->getId ();

        $result = $this->_getProductList ($storeId, $categoryId);

        return $result;
    }

    public function info ($productId, $productName = null)
    {
        $result = null;

        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId    = Mage::app ()->getStore ()->getId ();
        $categoryId = Mage::app ()->getStore ()->getRootCategoryId ();

        $collection = $this->_getProductCollection ($storeId, $categoryId);

        $collection->addAttributeToFilter ('sku_position', array ('eq' => $productId));

        $productId = $collection->getFirstItem ()->getId ();

        $result .= $this->_getBundleOptions ($productId, true);

        $result .= $this->_getProductOptions ($productId, true);

        return $result;
    }
}

