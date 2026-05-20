<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Sitef_Block_Payment_Info_Pinpad extends Mage_Payment_Block_Info
{
    protected $_transaction = null;

    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('gamuza/sitef/payment/info/pinpad.phtml');
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

        if ($status = $this->getTransaction()->getStatus()) {
            $data[Mage::helper('payment')->__('Status')] = $this->_ucwords ($status);
        }

        $amount = Mage::helper('core')->currency($this->getInfo()->getBaseAmountOrdered(), true, false);

        $data[Mage::helper('payment')->__('Amount')] = $amount;

        if ($name = $this->getTransaction()->getPaymentName()) {
            $data[Mage::helper('payment')->__('Name')] = $name;
        }

        if ($description = $this->getTransaction()->getPaymentDescription()) {
            $data[Mage::helper('payment')->__('Description')] = $description;
        }

        if ($sitefNsu = $this->getTransaction()->getSitefNsu()) {
            $data[Mage::helper('payment')->__('Sitef NSU')] = $sitefNsu;
        }

        if ($hostNsu = $this->getTransaction()->getHostNsu()) {
            $data[Mage::helper('payment')->__('Host NSU')] = $hostNsu;
        }

        if ($authorizationCode = $this->getTransaction()->getAuthorizationCode()) {
            $data[Mage::helper('payment')->__('Authorization Code')] = $authorizationCode;
        }

        if ($institutionName = $this->getTransaction()->getInstitutionName()) {
            $data[Mage::helper('payment')->__('Institution Name')] = $institutionName;
        }

        if ($cardNumber = $this->getTransaction()->getCardNumber()) {
            $data[Mage::helper('payment')->__('Card Number')] = $cardNumber;
        }

        if ($cardName = $this->getTransaction()->getCardName()) {
            $data[Mage::helper('payment')->__('Card Name')] = $cardName;
        }

        return $transport->setData(array_merge($transport->getData(), $data));
    }

    public function getTransaction()
    {
        if (!$this->_transaction) {
            $transactionId = $this->getInfo ()->getData (Gamuza_Sitef_Helper_Data::PAYMENT_ATTRIBUTE_SITEF_TRANS_ID);

            $this->_transaction = Mage::getModel ('sitef/pinpad_transaction')->load ($transactionId);
        }

        return $this->_transaction;
    }

    public function _ucwords ($text)
    {
        return Mage::helper ('sitef')->__(ucwords (str_replace ('_', ' ', $text)));
    }
}

