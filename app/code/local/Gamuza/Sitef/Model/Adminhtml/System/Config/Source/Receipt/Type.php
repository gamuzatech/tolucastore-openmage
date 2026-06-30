<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Receipt_Type config value selection
 */
class Gamuza_Sitef_Model_Adminhtml_System_Config_Source_Receipt_Type
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array('value' => Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_PURCHASE,          'label' => Mage::helper('sitef')->__('Purchase')),
            array('value' => Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_VOUCHER,           'label' => Mage::helper('sitef')->__('Voucher')),
            array('value' => Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_CHECK,             'label' => Mage::helper('sitef')->__('Bank Check')),
            array('value' => Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_PAYMENT,           'label' => Mage::helper('sitef')->__('Payment')),
            array('value' => Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_MANAGERIAL,        'label' => Mage::helper('sitef')->__('Managerial')),
            array('value' => Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_CB,                'label' => Mage::helper('sitef')->__('CB')),
            array('value' => Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_MOBILE_RECHARGE,   'label' => Mage::helper('sitef')->__('Mobile Recharge')),
            array('value' => Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_BONUS_RECHARGE,    'label' => Mage::helper('sitef')->__('Bonus Recharge')),
            array('value' => Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_GIFT_RECHARGE,     'label' => Mage::helper('sitef')->__('Gift Recharge')),
            array('value' => Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_SP_TRANS_RECHARGE, 'label' => Mage::helper('sitef')->__('SP Trans Recharge')),
            array('value' => Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_MEDICATIONS,       'label' => Mage::helper('sitef')->__('Medications')),
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
            Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_PURCHASE          => Mage::helper('sitef')->__('Purchase'),
            Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_VOUCHER           => Mage::helper('sitef')->__('Voucher'),
            Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_CHECK             => Mage::helper('sitef')->__('Bank Check'),
            Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_PAYMENT           => Mage::helper('sitef')->__('Payment'),
            Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_MANAGERIAL        => Mage::helper('sitef')->__('Managerial'),
            Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_CB                => Mage::helper('sitef')->__('CB'),
            Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_MOBILE_RECHARGE   => Mage::helper('sitef')->__('Mobile Recharge'),
            Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_BONUS_RECHARGE    => Mage::helper('sitef')->__('Bonus Recharge'),
            Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_GIFT_RECHARGE     => Mage::helper('sitef')->__('Gift Recharge'),
            Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_SP_TRANS_RECHARGE => Mage::helper('sitef')->__('SP Trans Recharge'),
            Gamuza_Sitef_Helper_Data::API_RECEIPT_TYPE_MEDICATIONS       => Mage::helper('sitef')->__('Medications'),
        );

        return $result;
    }
}

