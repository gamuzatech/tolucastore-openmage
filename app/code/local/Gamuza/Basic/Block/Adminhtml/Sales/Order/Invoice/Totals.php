<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml order invoice totals block
 */
class Gamuza_Basic_Block_Adminhtml_Sales_Order_Invoice_Totals
    extends Mage_Adminhtml_Block_Sales_Order_Invoice_Totals
{
    /**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    protected function _initTotals()
    {
        parent::_initTotals();

        $invoice = $this->getSource();
        $order = $invoice->getOrder();
        $payment = $order->getPayment();

        $this->addTotalBefore (new Varien_Object(array(
            'code'       => Gamuza_Basic_Model_Payment_Method_Deferred::CODE,
            'strong'     => true,
            'value'      => $payment->getDeferredFeeAmount(),
            'base_value' => $payment->getDeferredFeeAmount(),
            'label'      => $this->helper('basic')->__('Fee Amount'),
            'area'       => 'footer',
        )), 'grand_total');

        return $this;
    }
}

