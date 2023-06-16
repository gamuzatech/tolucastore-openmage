<?php
/*
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Cash on delivery payment method model
 */
class Gamuza_Mobile_Model_Payment_Method_Machineondelivery extends Mage_Payment_Model_Method_Cashondelivery
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code  = 'machineondelivery';

    /**
     * Cash On Delivery payment block paths
     *
     * @var string
     */
    protected $_formBlockType = 'mobile/payment_form_machineondelivery';
    protected $_infoBlockType = 'mobile/payment_info_machineondelivery';

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
        $info->setCcType($data->getCcType());

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
        $availableTypes = explode(',',$this->getConfigData('cctypes'));

        $ccType = '';

        if (!in_array($info->getCcType(), $availableTypes))
        {
            $errorMsg = Mage::helper('payment')->__('Credit card type is not allowed for this payment method.');
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

