<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Block_Payment_Info_Pix extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('gamuza/brazil/payment/info/pix.phtml');
    }

    /**
     * Prepare credit card related payment info
     *
     * @param Varien_Object|array $transport
     * @return Varien_Object
     */
    protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation)
        {
            return $this->_paymentSpecificInformation;
        }

        $transport = parent::_prepareSpecificInformation($transport);
        $data = array();

        $amount = Mage::helper('core')->currency($this->getInfo()->getBaseAmountOrdered(), true, false);
        $data[Mage::helper('payment')->__('Amount')] = $amount;

        $order = $this->_getOrder();

        if ($order && $order->getId())
        {
            $data[Mage::helper('payment')->__('Key')] = Mage::helper('brazil/pix')->_getKey($order);
        }

        return $transport->setData(array_merge($transport->getData(), $data));
    }

    public function _getOrder ()
    {
        if (!strcmp (Mage::app ()->getRequest ()->getActionName (), 'email'))
        {
            return null;
        }

        return Mage::registry ('current_order');
    }

    public function _getQRCodeUrl ($orderId)
    {
        $qrCodeUrl = Mage::app ()->getStore ()->isAdmin () ? 'admin_brazil/adminhtml_pix/qrcode' : 'brazil/pix/qrcode';

        $result = $this->getUrl ($qrCodeUrl, array(
            '_secure' => true, 'order_id' => $orderId,
            'form_key' => Mage::getSingleton ('core/session')->getFormKey ()
        ));

        return $result;
    }
}

