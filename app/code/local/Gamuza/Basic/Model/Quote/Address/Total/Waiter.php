<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Quote_Address_Total_Waiter
    extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    public const CODE = 'waiter';

    public function __construct()
    {
        $this->setCode(self::CODE);
    }

    /**
     * Collect totals information about waiter
     *
     * @return Gamuza_Basic_Model_Quote_Address_Total_Waiter
     */
    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);

        $this->_setAmount(0);
        $this->_setBaseAmount(0);

        if (!strcmp($address->getAddressType(), Mage_Customer_Model_Address_Abstract::TYPE_SHIPPING)
            || !Mage::getStoreConfigFlag(Gamuza_Basic_Helper_Data::XML_PATH_SALES_WAITER_OPTIONS_ACTIVE))
        {
            return $this;
        }

        $amount = Mage::getStoreConfigAsFloat(Gamuza_Basic_Helper_Data::XML_PATH_SALES_WAITER_OPTIONS_AMOUNT);

        $address->getQuote()->setWaiterAmount($amount);

        $this->_setAmount($amount)
            ->_setBaseAmount($amount);

        return $this;
    }

    /**
     * Add waiter totals information to address object
     *
     * @return $this
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        if (!strcmp($address->getAddressType(), Mage_Customer_Model_Address_Abstract::TYPE_SHIPPING)
            || !Mage::getStoreConfigFlag(Gamuza_Basic_Helper_Data::XML_PATH_SALES_WAITER_OPTIONS_ACTIVE))
        {
            return $this;
        }

        $title = Mage::helper('sales')->__('Waiter Amount');
        $value = Mage::getStoreConfigAsFloat(Gamuza_Basic_Helper_Data::XML_PATH_SALES_WAITER_OPTIONS_AMOUNT);

        $address->addTotal(array(
            'code' => $this->getCode(),
            'title' => $title,
            'value' => $value,
        ));

        return $this;
    }

    /**
     * Get Waiter label
     *
     * @return string
     */
    public function getLabel()
    {
        return Mage::helper('sales')->__('Waiter Amount');
    }
}

