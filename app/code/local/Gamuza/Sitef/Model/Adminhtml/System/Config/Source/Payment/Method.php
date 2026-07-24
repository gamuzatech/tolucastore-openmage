<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Payment_Method config value selection
 */
class Gamuza_Sitef_Model_Adminhtml_System_Config_Source_Payment_Method
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_NONE,           'label' => Mage::helper ('sitef')->__('None')),
            array ('value' => Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_AUTO,           'label' => Mage::helper ('sitef')->__('Auto')),
            array ('value' => Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_CHECK,          'label' => Mage::helper ('sitef')->__('Bank Check')),
            array ('value' => Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_DEBIT,          'label' => Mage::helper ('sitef')->__('Debit Card')),
            array ('value' => Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_CREDIT,         'label' => Mage::helper ('sitef')->__('Credit Card')),
            array ('value' => Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_DIGITAL_WALLET, 'label' => Mage::helper ('sitef')->__('Digital Wallet')),
            array ('value' => Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_PENDING_TRANSACTION, 'label' => Mage::helper ('sitef')->__('Pending Transaction')),
        );

        return $result;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $result = array(
            Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_NONE           => Mage::helper ('sitef')->__('None'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_AUTO           => Mage::helper ('sitef')->__('Auto'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_CHECK          => Mage::helper ('sitef')->__('Bank Check'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_DEBIT          => Mage::helper ('sitef')->__('Debit Card'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_CREDIT         => Mage::helper ('sitef')->__('Credit Card'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_DIGITAL_WALLET => Mage::helper ('sitef')->__('Digital Wallet'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_PENDING_TRANSACTION => Mage::helper ('sitef')->__('Pending Transaction'),
        );

        return $result;
    }
}

