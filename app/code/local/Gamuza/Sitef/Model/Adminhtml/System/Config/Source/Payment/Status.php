<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Payment_Status config value selection
 */
class Gamuza_Sitef_Model_Adminhtml_System_Config_Source_Payment_Status
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_CREATED,    'label' => Mage::helper ('sitef')->__('Created')),
            array ('value' => Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_UPDATED,    'label' => Mage::helper ('sitef')->__('Updated')),
            array ('value' => Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_AUTHORIZED, 'label' => Mage::helper ('sitef')->__('Authorized')),
            array ('value' => Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_CANCELED,   'label' => Mage::helper ('sitef')->__('Canceled')),
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
            Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_CREATED    => Mage::helper ('sitef')->__('Created'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_UPDATED    => Mage::helper ('sitef')->__('Updated'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_AUTHORIZED => Mage::helper ('sitef')->__('Authorized'),
            Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_CANCELED   => Mage::helper ('sitef')->__('Canceled'),
        );

        return $result;
    }
}

