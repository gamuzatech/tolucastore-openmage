<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Print config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Print
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRINT_NONE,       'label' => Mage::helper ('brazil')->__('None')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRINT_PORTRAIT,   'label' => Mage::helper ('brazil')->__('Portrait')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRINT_LANDSCAPE,  'label' => Mage::helper ('brazil')->__('Landscape')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRINT_SIMPLIFIED, 'label' => Mage::helper ('brazil')->__('Simplified')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRINT_NFCE,       'label' => Mage::helper ('brazil')->__('NFC-e')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRINT_ELETRONIC_MESSAGE, 'label' => Mage::helper ('brazil')->__('Eletronic Message')),
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
            Gamuza_Brazil_Helper_Data::NFE_PRINT_NONE       => Mage::helper ('brazil')->__('None'),
            Gamuza_Brazil_Helper_Data::NFE_PRINT_PORTRAIT   => Mage::helper ('brazil')->__('Portrait'),
            Gamuza_Brazil_Helper_Data::NFE_PRINT_LANDSCAPE  => Mage::helper ('brazil')->__('Landscape'),
            Gamuza_Brazil_Helper_Data::NFE_PRINT_SIMPLIFIED => Mage::helper ('brazil')->__('Simplified'),
            Gamuza_Brazil_Helper_Data::NFE_PRINT_NFCE       => Mage::helper ('brazil')->__('NFC-e'),
            Gamuza_Brazil_Helper_Data::NFE_PRINT_ELETRONIC_MESSAGE => Mage::helper ('brazil')->__('Eletronic Message'),
        );

        return $result;
    }
}

