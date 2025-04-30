<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

require_once (Mage::getModuleDir ('lib', 'Gamuza_Brazil') . DS . 'lib' . DS . 'phpqrcode' . DS . 'qrlib.php');

trait Gamuza_Brazil_Trait_Controller_Pix
{
	public function qrcodeAction ()
	{
        if (!$this->_validateFormKey ())
        {
            return $this->_throwException (Mage::helper ('brazil')->__('Invalid form key. Please refresh the page.'));
        }

        $isAdmin = Mage::app ()->getStore ()->isAdmin ();

        $order = $this->_getOrder ();

        if (!$order || !$order->getId ())
        {
            return $this->_throwException (Mage::helper ('brazil')->__('Order was not specified.'));
        }

        if (!$isAdmin && !$this->_getSession ()->isLoggedIn () && !$order->getCustomerIsGuest())
        {
            return $this->_throwException (Mage::helper ('brazil')->__('Customer is not logged in.'));
        }

        if (!$isAdmin && ($order->getCustomerId () != $this->_getSession ()->getCustomer ()->getId ()))
        {
            return $this->_throwException (Mage::helper ('brazil')->__('Order does not belong to the customer.'));
        }

        $key = $order->getPayment ()->getData (Gamuza_Brazil_Helper_Data::ORDER_PAYMENT_ATTRIBUTE_BRAZIL_PIX_KEY);

        if (!empty ($key))
        {
            return $this->_getQRCode ($key);
        }

        $key = $this->_getHelper ()->_getKey ($order);

        $this->_updatePaymentKey ($order, $key);

        return $this->_getQRCode ($key);
	}

    protected function _updatePaymentKey ($order, $key)
    {
        $order->getPayment ()->setData (Gamuza_Brazil_Helper_Data::ORDER_PAYMENT_ATTRIBUTE_BRAZIL_PIX_KEY, $key)->save ();
    }

    protected function _getQRCode ($key)
    {
        ob_start ();

        QRCode::png ($key, false, QR_ECLEVEL_H, 6, 0, false);

        $result = base64_encode (ob_get_contents ());

        ob_end_clean ();

        return $this->getResponse ()->setBody ("<img width='200' src='data:image/png;base64,{$result}' />");
    }

    protected function _getSession ()
    {
        return Mage::getSingleton ('customer/session');
    }

    protected function _getHelper ()
    {
        return Mage::helper ('brazil/pix');
    }

    protected function _getOrder ()
    {
        $orderId = $this->getRequest ()->getParam ('order_id');

        return Mage::getModel ('sales/order')->load ($orderId);
    }

    protected function _throwException ($message)
    {
        return $this->getResponse ()->setHttpResponseCode (400)->setBody ($message);
    }
}

