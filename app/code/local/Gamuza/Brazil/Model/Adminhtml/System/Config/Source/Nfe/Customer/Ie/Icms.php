<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Customer_Ie_Icms config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Customer_Ie_Icms
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CUSTOMER_IE_ICMS, 'label' => Mage::helper ('brazil')->__('ICMS Taxpayer')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CUSTOMER_IE_FREE, 'label' => Mage::helper ('brazil')->__('ICMS Free')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CUSTOMER_IE_NONE, 'label' => Mage::helper ('brazil')->__('ICMS None')),
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
            Gamuza_Brazil_Helper_Data::NFE_CUSTOMER_IE_ICMS => Mage::helper ('brazil')->__('ICMS Taxpayer'),
            Gamuza_Brazil_Helper_Data::NFE_CUSTOMER_IE_FREE => Mage::helper ('brazil')->__('ICMS Free'),
            Gamuza_Brazil_Helper_Data::NFE_CUSTOMER_IE_NONE => Mage::helper ('brazil')->__('ICMS None'),
        );

        return $result;
    }
}

