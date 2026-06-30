<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Card_Read_Status config value selection
 */
class Gamuza_Sitef_Model_Adminhtml_System_Config_Source_Card_Read_Status
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array('value' => Gamuza_Sitef_Helper_Data::API_CARD_READ_STATUS_SUCCESS,                   'label' => Mage::helper('sitef')->__('Success')),
            array('value' => Gamuza_Sitef_Helper_Data::API_CARD_READ_STATUS_FALLBACK_ERROR,            'label' => Mage::helper('sitef')->__('Fallback Error')),
            array('value' => Gamuza_Sitef_Helper_Data::API_CARD_READ_STATUS_APPLICATION_NOT_SUPPORTED, 'label' => Mage::helper('sitef')->__('Application Not Supported')),
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
            Gamuza_Sitef_Helper_Data::API_CARD_READ_STATUS_SUCCESS                   => Mage::helper('sitef')->__('Success'),
            Gamuza_Sitef_Helper_Data::API_CARD_READ_STATUS_FALLBACK_ERROR            => Mage::helper('sitef')->__('Fallback Error'),
            Gamuza_Sitef_Helper_Data::API_CARD_READ_STATUS_APPLICATION_NOT_SUPPORTED => Mage::helper('sitef')->__('Application Not Supported'),
        );

        return $result;
    }
}

