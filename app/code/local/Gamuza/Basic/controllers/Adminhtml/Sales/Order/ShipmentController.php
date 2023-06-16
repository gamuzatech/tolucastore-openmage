<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2021 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('controllers', 'Mage_Adminhtml') . DS . 'Sales' . DS . 'Order' . DS . 'ShipmentController.php');

/**
 * Adminhtml sales order shipment controller
 */
class Gamuza_Basic_Adminhtml_Sales_Order_ShipmentController
    extends Mage_Adminhtml_Sales_Order_ShipmentController
{
    /**
     * Save shipment
     * We can save only new shipment. Existing shipments are not editable
     *
     * @return null
     */
    public function saveAction ()
    {
        parent::saveAction ();

        $orderId = $this->getRequest ()->getParam ('order_id');

        if ($shipment = Mage::registry ('current_shipment'))
        {
            $order = $shipment->getOrder ();

            Mage::helper ('basic/sales_order_status')->shipped ($order);
        }

        $this->_redirect ('*/sales_order/view', array ('order_id' => $orderId));
    }
}

