<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Payment_Type config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Payment_Type
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_MONEY,        'label' => Mage::helper ('brazil')->__('Money')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_CHECK,        'label' => Mage::helper ('brazil')->__('Check')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_CREDIT_CARD,  'label' => Mage::helper ('brazil')->__('Credit Card')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_DEBIT_CARD,   'label' => Mage::helper ('brazil')->__('Debit Card')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_STORE_CREDIT, 'label' => Mage::helper ('brazil')->__('Store Credit')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_MEAL_TICKET,  'label' => Mage::helper ('brazil')->__('Meal Ticket')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_FOOD_VOUCHER, 'label' => Mage::helper ('brazil')->__('Food Voucher')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_GIFT_VOUCHER, 'label' => Mage::helper ('brazil')->__('Gift Voucher')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_FUEL_VOUCHER, 'label' => Mage::helper ('brazil')->__('Fuel Voucher')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_BANK_SLIP,    'label' => Mage::helper ('brazil')->__('Bank Slip')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_NONE,         'label' => Mage::helper ('brazil')->__('None')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_OTHER,        'label' => Mage::helper ('brazil')->__('Other')),
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
            Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_MONEY        => Mage::helper ('brazil')->__('Money'),
            Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_CHECK        => Mage::helper ('brazil')->__('Check'),
            Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_CREDIT_CARD  => Mage::helper ('brazil')->__('Credit Card'),
            Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_DEBIT_CARD   => Mage::helper ('brazil')->__('Debit Card'),
            Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_STORE_CREDIT => Mage::helper ('brazil')->__('Store Credit'),
            Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_MEAL_TICKET  => Mage::helper ('brazil')->__('Meal Ticket'),
            Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_FOOD_VOUCHER => Mage::helper ('brazil')->__('Food Voucher'),
            Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_GIFT_VOUCHER => Mage::helper ('brazil')->__('Gift Voucher'),
            Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_FUEL_VOUCHER => Mage::helper ('brazil')->__('Fuel Voucher'),
            Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_BANK_SLIP    => Mage::helper ('brazil')->__('Bank Slip'),
            Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_NONE         => Mage::helper ('brazil')->__('None'),
            Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_OTHER        => Mage::helper ('brazil')->__('Other'),
        );

        return $result;
    }
}

