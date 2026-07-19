<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Quote model
 */
class Gamuza_Basic_Model_Sales_Quote extends Mage_Sales_Model_Quote
{
    /**
     * Remove quote item by item identifier
     *
     * @param   int $itemId
     * @return  $this
     */
    public function removeItem ($itemId)
    {
        $item = $this->getItemById ($itemId);

        if ($item)
        {
            $item->setQuote ($this);

            Mage::dispatchEvent ('sales_quote_remove_item_before', array ('quote_item' => $item));

            /**
             * If we remove item from quote - we can't use multishipping mode
             */
            /*
            $this->setIsMultiShipping(false);
            $item->isDeleted(true);
            if ($item->getHasChildren())
            {
                foreach ($item->getChildren() as $child)
                {
                    $child->isDeleted(true);
                }
            }

            $parent = $item->getParentItem();
            if ($parent)
            {
                $parent->isDeleted(true);
            }

            Mage::dispatchEvent('sales_quote_remove_item', ['quote_item' => $item]);
            */
        }

        return parent::removeItem ($itemId);
    }
}

