<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Bundle product price block
 */
class Gamuza_Basic_Block_Bundle_Catalog_Product_Price
    extends Mage_Bundle_Block_Catalog_Product_Price
{
    /**
     * Convert block to html string
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!Mage::getStoreConfigFlag (Gamuza_Basic_Helper_Data::XML_PATH_CATALOG_PRODUCT_BUNDLE_PRICE))
        {
            return null;
        }

        return parent::_toHtml();
    }
}

