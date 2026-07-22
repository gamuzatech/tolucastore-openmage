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
     * Adding new item to quote
     *
     * @return  $this
     */
    public function addItem(Mage_Sales_Model_Quote_Item $item)
    {
        /**
         * Temporary workaround for purchase process: it is too dangerous to purchase more than one nominal item
         * or a mixture of nominal and non-nominal items, although technically possible.
         *
         * The problem is that currently it is implemented as sequential submission of nominal items and order, by one click.
         * It makes logically impossible to make the process of the purchase failsafe.
         * Proper solution is to submit items one by one with customer confirmation each time.
         */
        if ($item->isNominal() && $this->hasItems() || $this->hasNominalItems())
        {
            Mage::throwException(
                Mage::helper('sales')->__('Nominal item can be purchased standalone only. To proceed please remove other items from the quote.'),
            );
        }

        $item->setQuote($this);
        /*
        if (!$item->getId())
        {
            $this->getItemsCollection()->addItem($item);
            Mage::dispatchEvent('sales_quote_add_item', ['quote_item' => $item]);
        }
        */

        Mage::dispatchEvent('sales_quote_add_item_before', array('quote_item' => $item));

        return parent::addItem($item);
    }

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

