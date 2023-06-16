<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Sales Quote Item Model
 */
class Gamuza_Basic_Model_Sales_Quote_Item extends Mage_Sales_Model_Quote_Item
{
    public function isChildrenCalculated()
    {
        $product = $this->getParentItem ()
            ? $this->getParentItem ()->getProduct ()
            : $this->getProduct ()
        ;

        if (in_array ($product->getPriceView (), array (
            Gamuza_Basic_Helper_Data::PRODUCT_PRICE_VIEW_PRICE_RANGE,
            Gamuza_Basic_Helper_Data::PRODUCT_PRICE_VIEW_AS_LOW_AS,
            Gamuza_Basic_Helper_Data::PRODUCT_PRICE_VIEW_AS_HIGH_AS,
        )))
        {
            return parent::isChildrenCalculated ();
        }

        return false;
    }
}

