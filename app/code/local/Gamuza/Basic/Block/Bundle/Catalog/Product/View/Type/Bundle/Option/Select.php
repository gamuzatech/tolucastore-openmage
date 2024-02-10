<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Bundle option dropdown type renderer
 */
class Gamuza_Basic_Block_Bundle_Catalog_Product_View_Type_Bundle_Option_Select
    extends Mage_Bundle_Block_Catalog_Product_View_Type_Bundle_Option_Select
{
    /**
     * Get title price for selection product
     *
     * @param Mage_Catalog_Model_Product $_selection
     * @param bool $includeContainer
     * @return string
     * @throws Mage_Core_Model_Store_Exception
     */
    public function getSelectionTitlePrice ($_selection, $includeContainer = true)
    {
        if (!Mage::getStoreConfigFlag (Gamuza_Basic_Helper_Data::XML_PATH_CATALOG_PRODUCT_BUNDLE_OPTION_SELECT_PRICE))
        {
            return $_selection->getName ();
        }

        return parent::getSelectionTitlePrice ($_selection, $includeContainer);
    }
}

