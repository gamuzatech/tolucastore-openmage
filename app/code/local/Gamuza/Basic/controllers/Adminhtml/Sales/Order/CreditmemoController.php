<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2021 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('controllers', 'Mage_Adminhtml') . DS . 'Sales' . DS . 'Order' . DS . 'CreditmemoController.php');

/**
 * Adminhtml sales order creditmemo controller
 */
class Gamuza_Basic_Adminhtml_Sales_Order_CreditmemoController
    extends Mage_Adminhtml_Sales_Order_CreditmemoController
{
    /**
     * Save creditmemo
     * We can save only new creditmemo. Existing creditmemos are not editable
     */
    public function saveAction ()
    {
        parent::saveAction ();

        $orderId = $this->getRequest ()->getParam ('order_id');

        if ($creditmemo = Mage::registry ('current_creditmemo'))
        {
            $order = $creditmemo->getOrder ();

            Mage::helper ('basic/sales_order_status')->refunded ($order);
        }

        $this->_redirect ('*/sales_order/view', array ('order_id' => $orderId));
    }
}

