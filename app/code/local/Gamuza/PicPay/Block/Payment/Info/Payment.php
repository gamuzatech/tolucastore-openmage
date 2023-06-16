<?php
/**
 * @package     Gamuza_PicPay
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PicPay_Block_Payment_Info_Payment extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('gamuza/picpay/payment/info/default.phtml');
    }

    /**
     * Prepare credit card related payment info
     *
     * @param Varien_Object|array $transport
     * @return Varien_Object
     */
    protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }

        $transport = parent::_prepareSpecificInformation($transport);
        $data = array();

        if ($picpayStatus = $this->getInfo()->getPicpayStatus()) {
            $data[Mage::helper('payment')->__('Status')] = $this->_ucwords ($picpayStatus);
        }

        $amount = Mage::helper('core')->currency($this->getInfo()->getBaseAmountOrdered(), true, false);
        $data[Mage::helper('payment')->__('Amount')] = $amount;

        if ($picpayUrl = $this->getInfo()->getPicpayUrl()) {
            $data[Mage::helper('payment')->__('Payment URL')] = $this->_url($picpayUrl);
        }

        if (Mage::app()->getStore()->isAdmin()) {
            if ($extPaymentId = $this->getExtPaymentId()) {
                $data[Mage::helper('payment')->__('Ext. Payment ID')] = $extPaymentId;
            }
        } // isAdmin

        return $transport->setData(array_merge($transport->getData(), $data));
    }

    /**
     * Retrieve External Payment ID
     *
     * @return string
     */
    public function getExtPaymentId()
    {
        return $this->getInfo()->getData(Gamuza_PicPay_Helper_Data::PAYMENT_ATTRIBUTE_EXT_PAYMENT_ID);
    }

    public function _ucwords ($text)
    {
        return Mage::helper ('picpay')->__(ucwords (str_replace ('_', ' ', $text)));
    }

    public function _url ($url)
    {
        return Mage::helper ('picpay')->__("<a target='_blank' href='%s'>See on PicPay</a>", $url);
    }
}

