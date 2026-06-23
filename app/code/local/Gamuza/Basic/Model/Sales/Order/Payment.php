<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Order payment information
 */
class Gamuza_Basic_Model_Sales_Order_Payment
    extends Mage_Sales_Model_Order_Payment
{
    /**
     * Order payment either online
     * Updates transactions hierarchy, if required
     * Prevents transaction double processing
     * Updates payment totals, updates order status and adds proper comments
     *
     * @param float $amount
     * @return $this
     */
    protected function _order ($amount)
    {
        $order = $this->getOrder ();
        $quote = $order->getQuote ();

        $collection = Mage::getModel ('basic/order_payment')->getCollection ()
            ->addFieldToFilter ('quote_id',   array ('eq' => $quote->getId ()))
            ->addFieldToFilter ('is_default', array ('eq' => '1'))
        ;

        if ($collection->getSize () > 0)
        {
            $payment = $collection->getFirstItem ();

            $amount = $payment->getAmount ();
        }

        return parent::_order ($amount);
    }
}

