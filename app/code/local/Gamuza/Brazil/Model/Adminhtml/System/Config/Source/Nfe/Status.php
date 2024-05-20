<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Status config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Status
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_STATUS_CREATED,    'label' => Mage::helper ('brazil')->__('Created')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_STATUS_SIGNED,     'label' => Mage::helper ('brazil')->__('Signed')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_STATUS_ISSUED,     'label' => Mage::helper ('brazil')->__('Issued')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED, 'label' => Mage::helper ('brazil')->__('Authorized')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_STATUS_DENIED,     'label' => Mage::helper ('brazil')->__('Denied')),
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
            Gamuza_Brazil_Helper_Data::NFE_STATUS_CREATED    => Mage::helper ('brazil')->__('Created'),
            Gamuza_Brazil_Helper_Data::NFE_STATUS_SIGNED     => Mage::helper ('brazil')->__('Signed'),
            Gamuza_Brazil_Helper_Data::NFE_STATUS_ISSUED     => Mage::helper ('brazil')->__('Issued'),
            Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED => Mage::helper ('brazil')->__('Authorized'),
            Gamuza_Brazil_Helper_Data::NFE_STATUS_DENIED     => Mage::helper ('brazil')->__('Denied'),
        );

        return $result;
    }
}

