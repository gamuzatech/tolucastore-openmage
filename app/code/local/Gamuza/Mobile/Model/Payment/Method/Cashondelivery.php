<?php
/*
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Cash on delivery payment method model
 */
class Gamuza_Mobile_Model_Payment_Method_Cashondelivery extends Mage_Payment_Model_Method_Cashondelivery
{
    /**
     * Cash On Delivery payment block paths
     *
     * @var string
     */
    protected $_formBlockType = 'mobile/payment_form_cashondelivery';
    protected $_infoBlockType = 'mobile/payment_info_cashondelivery';

    /**
     * Assign data to info model instance
     *
     * @param   mixed $data
     * @return  Mage_Payment_Model_Info
     */
    public function assignData($data)
    {
        if (!($data instanceof Varien_Object))
        {
            $data = new Varien_Object($data);
        }

        $info = $this->getInfoInstance();
        $info->unsAdditionalInformation ();

        if ($data->getChangeType() !== null)
        {
            $info->setAdditionalInformation('change_type', $data->getChangeType());
        }

        if ($data->getChangeType() !== null)
        {
            $info->setAdditionalInformation('cash_amount', $data->getCashAmount());
        }

        if ($data->getChangeType() !== null)
        {
            $info->setAdditionalInformation('change_amount', 0);
        }

        return $this;
    }

    /**
     * Validate payment method information object
     *
     * @param   Mage_Payment_Model_Info $info
     * @return  Mage_Payment_Model_Abstract
     */
    public function validate()
    {
        /*
        * calling parent validate function
        */
        parent::validate();

        $info = $this->getInfoInstance();
        $errorMsg = false;
/*
        $availableTypes = array('0', '1');

        if (!in_array($info->getAdditionalInformation('change_type'), $availableTypes))
        {
            $errorMsg = Mage::helper('payment')->__('Change type is not allowed for this payment method.');
        }
*/
        if (!strcmp($info->getAdditionalInformation('change_type'), '1'))
        {
            $cashAmount = $info->getAdditionalInformation('cash_amount');
            $cashAmount = str_replace (',', '.', $cashAmount);
            $quoteOrder = $info->getQuote() ? $info->getQuote() : $info->getOrder();
            $baseGrandTotal = $quoteOrder->getBaseGrandTotal();

            if (empty($cashAmount) || !(floatval($cashAmount) >= ceil($baseGrandTotal)))
            {
                $errorMsg = Mage::helper('payment')->__('Cash amount must be equal or greater than %s.', Mage::helper('core')->currency(ceil($baseGrandTotal), true, false));
            }

            $info->setAdditionalInformation('change_amount', $cashAmount - $baseGrandTotal);
        }

        if($errorMsg)
        {
            Mage::throwException($errorMsg);
        }

        return $this;
    }

    public function isApplicableToQuote($quote, $checksBitMask)
    {
        return Mage_Payment_Model_Method_Abstract::isApplicableToQuote($quote, $checksBitMask);
    }
}

