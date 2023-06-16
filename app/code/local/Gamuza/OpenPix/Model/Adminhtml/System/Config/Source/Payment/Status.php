<?php
/**
 * @package     Gamuza_OpenPix
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Payment_Status config value selection
 */
class Gamuza_OpenPix_Model_Adminhtml_System_Config_Source_Payment_Status
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_ACTIVE,    'label' => Mage::helper ('openpix')->__('Active')),
            array ('value' => Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_COMPLETED, 'label' => Mage::helper ('openpix')->__('Completed')),
            array ('value' => Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_EXPIRED,   'label' => Mage::helper ('openpix')->__('Expired')),
            array ('value' => Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_ERROR,     'label' => Mage::helper ('openpix')->__('Error')),
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
            Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_ACTIVE    => Mage::helper ('openpix')->__('Active'),
            Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_COMPLETED => Mage::helper ('openpix')->__('Completed'),
            Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_EXPIRED   => Mage::helper ('openpix')->__('Expired'),
            Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_ERROR     => Mage::helper ('openpix')->__('Error'),
        );

        return $result;
    }
}

