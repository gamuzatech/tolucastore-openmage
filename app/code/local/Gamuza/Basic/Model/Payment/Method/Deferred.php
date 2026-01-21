<?php
/*
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Deferred payment method model
 */
class Gamuza_Basic_Model_Payment_Method_Deferred extends Mage_Payment_Model_Method_Cashondelivery
{
    public const CODE = 'basic_deferred_payment';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $_code = self::CODE;

    /**
     * Deferred payment block paths
     *
     * @var string
     */
    protected $_formBlockType = 'basic/payment_form_deferred';
    protected $_infoBlockType = 'basic/payment_info_deferred';

    private $_percentageFees = null;

    public function __construct()
    {
        parent::__construct();

        $this->_percentageFees = Mage::getStoreConfig (Gamuza_Basic_Helper_Data::XML_PATH_PAYMENT_BASIC_DEFERRED_PAYMENT_PERCENTAGE_FEES);
    }

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

        if ($data->getDeferredInstallmentsQty() !== null)
        {
            $info->setDeferredInstallmentsQty($data->getDeferredInstallmentsQty());
        }

        if ($data->getDeferredIntervalDays() !== null)
        {
            $info->setDeferredIntervalDays($data->getDeferredIntervalDays());
        }

        if ($data->getDeferredFirstDueDays() !== null)
        {
            $info->setDeferredFirstDueDays($data->getDeferredFirstDueDays());
        }

        if ($this->_percentageFees !== null)
        {
            $info->setDeferredPercentageFees ($this->_percentageFees);
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

        $installmentsQty = $info->getDeferredInstallmentsQty() ?? -1;
        $availableQtys = $this->_getInstallmentsAvailableQtys();

        if (!in_array($installmentsQty, array_keys($availableQtys)))
        {
            $errorMsg = Mage::helper('payment')->__('Installments qty are not allowed for this payment method.');
        }

        $intervalDays = $info->getDeferredIntervalDays() ?? -1;
        $availableDays = $this->_getIntervalAvailableDays();

        if (!in_array($intervalDays, array_keys($availableDays)))
        {
            $errorMsg = Mage::helper('payment')->__('Interval days are not allowed for this payment method.');
        }

        $firstDueDays = $info->getDeferredFirstDueDays() ?? -1;
        $availableDays = $this->_getFirstDueAvailableDays();

        if (!in_array($firstDueDays, array_keys($availableDays)))
        {
            $errorMsg = Mage::helper('payment')->__('First due days are not allowed for this payment method.');
        }

        if ($errorMsg)
        {
            Mage::throwException($errorMsg);
        }

        return $this;
    }

    public function isApplicableToQuote($quote, $checksBitMask)
    {
        return Mage_Payment_Model_Method_Abstract::isApplicableToQuote($quote, $checksBitMask);
    }

    /**
     * Retrieve availables installments qtys
     *
     * @return array
     */
    public function _getInstallmentsAvailableQtys ()
    {
        $qtys = Mage::getModel ('basic/adminhtml_system_config_source_payment_deferred_installmentsQty')->toArray ();

        $availableQtys = $this->getConfigData ('installments_qty');

        if ($availableQtys)
        {
            $availableQtys = explode (',', $availableQtys);

            foreach ($qtys as $code => $name)
            {
                if (!in_array ($code, $availableQtys))
                {
                    unset ($qtys [$code]);
                }
            }
        }

        return $qtys;
    }

    /**
     * Retrieve availables interval days
     *
     * @return array
     */
    public function _getIntervalAvailableDays ()
    {
        $days = Mage::getModel ('basic/adminhtml_system_config_source_payment_deferred_intervalDays')->toArray ();

        $availableDays = $this->getConfigData ('interval_days');

        if ($availableDays)
        {
            $availableDays = explode (',', $availableDays);

            foreach ($days as $code => $name)
            {
                if (!in_array ($code, $availableDays))
                {
                    unset ($days [$code]);
                }
            }
        }

        return $days;
    }

    /**
     * Retrieve availables first due days
     *
     * @return array
     */
    public function _getFirstDueAvailableDays()
    {
        $days = Mage::getModel ('basic/adminhtml_system_config_source_payment_deferred_firstDueDays')->toArray ();

        $availableDays = $this->getConfigData ('first_due_days');

        if ($availableDays)
        {
            $availableDays = explode (',', $availableDays);

            foreach ($days as $code => $name)
            {
                if (!in_array($code, $availableDays))
                {
                    unset ($days [$code]);
                }
            }
        }

        return $days;
    }
}

