<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Sales_Resource_Quote extends Mage_Sales_Model_Resource_Quote
{
    /**
     * Get reserved order id
     *
     * @param Mage_Sales_Model_Quote $quote
     * @return string
     */
    public function getReservedOrderId($quote)
    {
        $storeId = (int)$quote->getStoreId();

        return Mage::getSingleton('eav/config')
            ->getEntityType(Mage_Sales_Model_Order::ENTITY)
            ->setQuote($quote)
            ->fetchNewIncrementId($storeId);
    }
}

