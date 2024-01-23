<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Version config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Version
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_VERSION_3_10, 'label' => Mage::helper ('brazil')->__('3.10')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_VERSION_4_00, 'label' => Mage::helper ('brazil')->__('4.00')),
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
            Gamuza_Brazil_Helper_Data::NFE_VERSION_3_10 => Mage::helper ('brazil')->__('3.10'),
            Gamuza_Brazil_Helper_Data::NFE_VERSION_4_00 => Mage::helper ('brazil')->__('4.00'),
        );

        return $result;
    }
}

