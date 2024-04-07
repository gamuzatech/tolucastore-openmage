<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Certificate_Type config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Certificate_Type
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CERTIFICATE_A1_REPOSITORY, 'label' => Mage::helper ('brazil')->__('A1')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CERTIFICATE_A1_FILE,       'label' => Mage::helper ('brazil')->__('A1 File')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CERTIFICATE_A1_BYTE_ARRAY, 'label' => Mage::helper ('brazil')->__('A1 Byte Array')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CERTIFICATE_A3,            'label' => Mage::helper ('brazil')->__('A3')),
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
            Gamuza_Brazil_Helper_Data::NFE_CERTIFICATE_A1_REPOSITORY  => Mage::helper ('brazil')->__('A1'),
            Gamuza_Brazil_Helper_Data::NFE_CERTIFICATE_A1_FILE        => Mage::helper ('brazil')->__('A1 File'),
            Gamuza_Brazil_Helper_Data::NFE_CERTIFICATE_A1_BYTE_ARRAY  => Mage::helper ('brazil')->__('A1 Byte Array'),
            Gamuza_Brazil_Helper_Data::NFE_CERTIFICATE_A3             => Mage::helper ('brazil')->__('A3'),
        );

        return $result;
    }
}

