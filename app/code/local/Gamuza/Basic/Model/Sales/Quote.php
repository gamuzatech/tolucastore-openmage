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
            Mage::dispatchEvent ('sales_quote_remove_item_before', array ('quote_item' => $item));
        }

        return parent::removeItem ($itemId);
    }
}

