<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Order item render block
 */
class Gamuza_Basic_Block_Bundle_Sales_Order_Items_Renderer
    extends Mage_Bundle_Block_Sales_Order_Items_Renderer
{
    /**
     * @param Mage_Sales_Model_Order_Item $item
     * @return string
     */
    public function getValueHtml ($item)
    {
        if (!Mage::getStoreConfigFlag (Gamuza_Basic_Helper_Data::XML_PATH_CATALOG_PRODUCT_BUNDLE_OPTION_SELECT_PRICE))
        {
            return $this->escapeHtml ($item->getName ());
        }

        return parent::getValueHtml ($item);
    }
}

