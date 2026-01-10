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
    public function items ($categoryId, $categoryName = null, $productName = null)
    {
        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $collection = $this->_getCategoryCollection ($storeId)
            ->addFieldToFilter ('main_table.position', array ('eq' => $categoryId))
        ;

        $_categoryId = $collection->getFirstItem ()->getId ();

        $_category = Mage::getModel ('catalog/category')->load ($_categoryId);

        if ($_category && $_category->getId ())
        {
            if ($categoryName != null && !str_contains ($_category->getName (), $categoryName))
            {
                /*
                $result = Mage::helper ('bot/message')->getCategoryInvalidIdOrNameText ($categoryId, $categoryName, $_category) . PHP_EOL . PHP_EOL
                    . Mage::helper ('bot/message')->getProductNotAddedToCartText () . PHP_EOL . PHP_EOL
                ;

                return $result;
                */

                $collection = $this->_getCategoryCollection ($storeId)
                    ->addFieldToFilter ('main_table.name', array ('like' => $categoryName . '%'))
                ;

                $_categoryId = $collection->getFirstItem ()->getId ();
            }
        }

        $result = $this->_getProductList ($storeId, $_categoryId, $productName);

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

        $_productId = $collection->getFirstItem ()->getId ();

        $_product = Mage::getModel ('catalog/product')->load ($_productId);

        if ($_product && $_product->getId ())
        {
            if ($productName != null && !str_contains ($_product->getName (), $productName))
            {
                /*
                $result = Mage::helper ('bot/message')->getProductInvalidIdOrNameText ($productId, $productName, $_product) . PHP_EOL . PHP_EOL
                    . Mage::helper ('bot/message')->getProductNotAddedToCartText () . PHP_EOL . PHP_EOL
                ;

                return $result;
                */

                $collection = $this->_getProductCollection ($storeId, $categoryId)
                    ->addAttributeToFilter ('name', array ('like' => $productName . '%'))
                ;

                $_productId = $collection->getFirstItem ()->getId ();

                $_product = Mage::getModel ('catalog/product')->load ($_productId);
            }
        }

        if ($_product && $_product->getId ())
        {
            $strLen = self::PRODUCT_ID_LENGTH - strlen ($_product->getSkuPosition ());
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            if (!floatval ($_product->getFinalPrice ()))
            {
                $_product->setData ('final_price', $_product->getData ('price'));
            }

            $productPrice = Mage::helper ('core')->currency ($_product->getFinalPrice (), true, false);

            $result .= sprintf ('*%s*%s%s *%s*', $_product->getSkuPosition (), $strPad, $_product->getName (), $productPrice) . PHP_EOL . PHP_EOL
                // . $_product->getShortDescription () . PHP_EOL . PHP_EOL
            ;
        }

        $result .= $this->_getBundleOptions ($_productId, true);

        $result .= $this->_getProductOptions ($_productId, true);

        return $result;
    }
}

