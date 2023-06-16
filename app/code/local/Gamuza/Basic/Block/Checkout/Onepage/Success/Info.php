<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Block_Checkout_Onepage_Success_Info extends Mage_Core_Block_Template
{
    protected function _construct ()
    {
        $this->_loadValidOrder ();

        $this->setTemplate ('gamuza/basic/checkout/onepage/success/info.phtml');
    }

    public function _loadValidOrder ()
    {
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        $order = Mage::getModel('sales/order')->load($orderId);

        Mage::register ('current_order', $order);
    }

    public function getOrder ()
    {
        return Mage::registry ('current_order');
    }

    public function getPaymentInfoHtml ()
    {
        return $this->helper('payment')->getInfoBlock($this->getOrder()->getPayment())->toHtml ();
    }
}

