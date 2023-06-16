<?php
/**
 * @package     Gamuza_PicPay
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Payment_Status config value selection
 */
class Gamuza_PicPay_Model_Adminhtml_System_Config_Source_Payment_Status
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_CREATED,     'label' => Mage::helper ('picpay')->__('Created')),
            array ('value' => Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_EXPIRED,     'label' => Mage::helper ('picpay')->__('Expired')),
            array ('value' => Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_ANALYSIS,    'label' => Mage::helper ('picpay')->__('Analysis')),
            array ('value' => Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_PAID,        'label' => Mage::helper ('picpay')->__('Paid')),
            array ('value' => Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_COMPLETED,   'label' => Mage::helper ('picpay')->__('Completed')),
            array ('value' => Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_REFUNDED,    'label' => Mage::helper ('picpay')->__('Refunded')),
            array ('value' => Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_CHARGEDBACK, 'label' => Mage::helper ('picpay')->__('Chargedback')),
            array ('value' => Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_ERROR,       'label' => Mage::helper ('picpay')->__('Error')),
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
            Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_CREATED     => Mage::helper ('picpay')->__('Created'),
            Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_EXPIRED     => Mage::helper ('picpay')->__('Expired'),
            Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_ANALYSIS    => Mage::helper ('picpay')->__('Analysis'),
            Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_PAID        => Mage::helper ('picpay')->__('Paid'),
            Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_COMPLETED   => Mage::helper ('picpay')->__('Completed'),
            Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_REFUNDED    => Mage::helper ('picpay')->__('Refunded'),
            Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_CHARGEDBACK => Mage::helper ('picpay')->__('Chargedback'),
            Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_ERROR       => Mage::helper ('picpay')->__('Error'),
        );

        return $result;
    }
}

