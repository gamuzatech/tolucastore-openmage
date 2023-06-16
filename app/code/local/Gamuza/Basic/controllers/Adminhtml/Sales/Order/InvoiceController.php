<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2021 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('controllers', 'Mage_Adminhtml') . DS . 'Sales' . DS . 'Order' . DS . 'InvoiceController.php');

/**
 * Adminhtml sales order edit controller
 */
class Gamuza_Basic_Adminhtml_Sales_Order_InvoiceController
    extends Mage_Adminhtml_Sales_Order_InvoiceController
{
    /**
     * Save invoice
     * We can save only new invoice. Existing invoices are not editable
     */
    public function saveAction ()
    {
        parent::saveAction ();

        $orderId = $this->getRequest ()->getParam ('order_id');

        if ($invoice = Mage::registry ('current_invoice'))
        {
            $order = $invoice->getOrder ();

            Mage::helper ('basic/sales_order_status')->paid ($order);
        }

        $this->_redirect ('*/sales_order/view', array ('order_id' => $orderId));
    }
}

