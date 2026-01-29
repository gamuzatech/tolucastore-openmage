<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Invoice_Total_Deferred
    extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    protected $_code = Gamuza_Basic_Model_Payment_Method_Deferred::CODE;

    public function collect (Mage_Sales_Model_Order_Invoice $invoice)
    {
        $order   = $invoice->getOrder ();
        $payment = $order->getPayment ();
        $fee     = $payment->getDeferredFeeAmount ();

        $invoice->setGrandTotal(
            $invoice->getGrandTotal() + $fee
        );

        $invoice->setBaseGrandTotal(
            $invoice->getBaseGrandTotal() + $fee
        );

        return $this;
    }
}


