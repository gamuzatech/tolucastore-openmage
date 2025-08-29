<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * API Resource Abstract
 */
class Toluca_Bot_Model_Api_Resource_Abstract extends Mage_Api_Model_Resource_Abstract
{
    const CATEGORY_ID_LENGTH = Toluca_Bot_Helper_Data::CATEGORY_ID_LENGTH;
    const PRODUCT_ID_LENGTH  = Toluca_Bot_Helper_Data::PRODUCT_ID_LENGTH;
    const OPTION_ID_LENGTH   = Toluca_Bot_Helper_Data::OPTION_ID_LENGTH;
    const VALUE_ID_LENGTH    = Toluca_Bot_Helper_Data::VALUE_ID_LENGTH;
    const SHIPPING_ID_LENGTH = Toluca_Bot_Helper_Data::SHIPPING_ID_LENGTH;
    const PAYMENT_ID_LENGTH  = Toluca_Bot_Helper_Data::PAYMENT_ID_LENGTH;
    const CCTYPE_ID_LENGTH   = Toluca_Bot_Helper_Data::CCTYPE_ID_LENGTH;
    const QUANTITY_LENGTH    = Toluca_Bot_Helper_Data::QUANTITY_LENGTH;

    const COMMAND_ZERO = Toluca_Bot_Helper_Data::COMMAND_ZERO;
    const COMMAND_ONE  = Toluca_Bot_Helper_Data::COMMAND_ONE;
    const COMMAND_OK   = Toluca_Bot_Helper_Data::COMMAND_OK;

    const DEFAULT_CUSTOMER_EMAIL  = Toluca_Bot_Helper_Data::DEFAULT_CUSTOMER_EMAIL;
    const DEFAULT_CUSTOMER_TAXVAT = Toluca_Bot_Helper_Data::DEFAULT_CUSTOMER_TAXVAT;

    protected function _getCategoryCollection ($storeId)
    {
        $websiteId = Mage::app ()->getStore ($storeId)->getWebsite ()->getId ();

        $collection = Mage::getModel ('catalog/category')->getCollection ()
            ->addIsActiveFilter ()
            ->addNameToResult ()
            ->addFieldToFilter ('level', array ('gteq' => '2'))
        ;

        $collection->getSelect ()
            ->where ('main_table.is_active = 1')
            ->group ('main_table.entity_id')
            ->order ('main_table.position')
            ->join(
                array ('ccp' => Mage::getSingleton ('core/resource')->getTableName ('catalog_category_product')),
                'main_table.entity_id = ccp.category_id',
                array(
                    'products_count' => 'COUNT(ccp.product_id)',
                )
            )
            ->join(
                array ('cpf' => Mage::getSingleton ('core/resource')->getTableName ('catalog_product_flat_' . $storeId)),
                'ccp.product_id = cpf.entity_id',
                array ()
            )
            ->where ('cpf.status = ?',     Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->where ('cpf.visibility = ?', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
            ->join(
                array ('ciss' => Mage::getSingleton ('core/resource')->getTableName ('cataloginventory_stock_status')),
                'cpf.entity_id = ciss.product_id AND ciss.stock_status = 1',
                array ()
            )
            ->where ('ciss.website_id = ?', $websiteId)
        ;

        return $collection;
    }

    protected function _getCategoryList ($storeId)
    {
        $result = null;

        $collection = $this->_getCategoryCollection ($storeId);

        foreach ($collection as $category)
        {
            $strLen = self::CATEGORY_ID_LENGTH - strlen ($category->getPosition ());
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            $result .= sprintf ('*%s*%s%s', $category->getPosition (), $strPad, $category->getName ()) . PHP_EOL;
        }

        return $result;
    }

    protected function _getProductCollection ($storeId, $categoryId)
    {
        $websiteId = Mage::app ()->getStore ($storeId)->getWebsite ()->getId ();

        $category = Mage::getModel ('catalog/category')->load ($categoryId);

        $collection = Mage::getModel ('catalog/product')->getCollection ()
            ->addAttributeToSelect ('name')
            ->addAttributeToSelect ('price')
            ->addAttributeToSelect ('special_price')
            ->addAttributeToSelect ('special_from_date')
            ->addAttributeToSelect ('special_to_date')
            ->addAttributeToSelect ('sku_position')
            ->addAttributeToFilter ('type_id', array ('in' => array (
                Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
                Mage_Catalog_Model_Product_Type::TYPE_BUNDLE
            )))
            ->addCategoryFilter ($category)
            ->setVisibility (Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
            ->addFinalPrice ()
        ;

        $collection->getSelect ()
            ->join(
                array ('ciss' => Mage::getSingleton ('core/resource')->getTableName ('cataloginventory_stock_status')),
                'e.entity_id = ciss.product_id AND ciss.stock_status = 1',
                array ()
            )
            ->where ('ciss.website_id = ?', $websiteId)
        ;

        return $collection;
    }

    protected function _getProductList ($storeId, $categoryId)
    {
        $result = null;

        $collection = $this->_getProductCollection ($storeId, $categoryId);

        foreach ($collection as $product)
        {
            $strLen = self::PRODUCT_ID_LENGTH - strlen ($product->getSkuPosition ());
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            if (!floatval ($product->getFinalPrice ()))
            {
                $product->setData ('final_price', $product->getData ('price'));
            }

            $productPrice = Mage::helper ('core')->currency ($product->getFinalPrice (), true, false);

            $result .= sprintf ('*%s*%s%s *%s*', $product->getSkuPosition (), $strPad, $product->getName (), $productPrice) . PHP_EOL;
        }

        return $result;
    }

    protected function _getBundleOptions ($productId)
    {
        $collection = Mage::getModel ('bundle/option')->getCollection ()
            ->setProductIdFilter ($productId)
            ->joinValues (Mage_Core_Model_App::ADMIN_STORE_ID)
            ->setPositionOrder ()
        ;

        foreach ($collection as $option)
        {
            $strLen = self::OPTION_ID_LENGTH - strlen ($option->getPosition ());
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            $required = $option->getRequired () ? sprintf (' *(%s)* ', Mage::helper ('bot')->__('required')) : null;

            $result .= sprintf ('*%s*%s%s%s', $option->getPosition (), $strPad, $option->getDefaultTitle (), $required) . PHP_EOL;
        }

        return $result;
    }

    protected function _getBundleSelections ($option)
    {
        $collection = Mage::getModel ('bundle/selection')->getCollection ()
            ->addAttributeToFilter ('name', array ('notnull' => true))
            ->addAttributeToSelect ('price')
            ->setOptionIdsFilter ($option->getId ())
            ->setPositionOrder ()
        ;

        foreach ($collection as $selection)
        {
            $strLen = self::VALUE_ID_LENGTH - strlen ($selection->getPosition ());
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            $selectionPrice = Mage::helper ('core')->currency ($selection->getFinalPrice (), true, false);

            $result .= sprintf ('*%s*%s%s *%s*', $selection->getPosition (), $strPad, $selection->getName (), $selectionPrice) . PHP_EOL;
        }

        return $result;
    }

    protected function _getProductOptions ($productId)
    {
        $product = Mage::getModel ('catalog/product')->load ($productId);

        foreach ($product->getOptions () as $option)
        {
            $strLen = self::OPTION_ID_LENGTH - strlen ($option->getSortOrder ());
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            $require = $option->getIsRequire () ? sprintf (' *(%s)* ', Mage::helper ('bot')->__('required')) : null;

            $result .= sprintf ('*%s*%s%s%s', $option->getSortOrder (), $strPad, $option->getTitle (), $require) . PHP_EOL;
        }

        return $result;
    }

    protected function _getProductValues ($option)
    {
        foreach ($option->getValues () as $value)
        {
            $strLen = self::VALUE_ID_LENGTH - strlen ($value->getSortOrder ());
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            $valuePrice = Mage::helper ('core')->currency ($value->getPrice (), true, false);

            $result .= sprintf ('*%s*%s%s *%s*', $value->getSortOrder (), $strPad, $value->getTitle (), $valuePrice) . PHP_EOL;
        }

        return $result;
    }
}

