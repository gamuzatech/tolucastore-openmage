<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Order_Total_Waiter
    extends Mage_Sales_Model_Order_Total_Abstract
{
    public function collect (Mage_Sales_Model_Order_Invoice $invoice)
    {
        $order   = $invoice->getOrder ();
        $payment = $order->getPayment ();
        $fee     = 23.45; // $payment->getWaiterFeeAmount ();

        $invoice->setGrandTotal(
            $invoice->getGrandTotal() + $fee
        );

        $invoice->setBaseGrandTotal(
            $invoice->getBaseGrandTotal() + $fee
        );

        return $this;
    }
}

