<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Certificate_Middleware config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Certificate_Middleware
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CERTIFICATE_A3_MIDDLEWARE_DEFAULT, 'label' => Mage::helper ('brazil')->__('Default')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CERTIFICATE_A3_MIDDLEWARE_SAFENET, 'label' => Mage::helper ('brazil')->__('SafeNet')),
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
            Gamuza_Brazil_Helper_Data::NFE_CERTIFICATE_A3_MIDDLEWARE_DEFAULT => Mage::helper ('brazil')->__('Default'),
            Gamuza_Brazil_Helper_Data::NFE_CERTIFICATE_A3_MIDDLEWARE_SAFENET => Mage::helper ('brazil')->__('SafeNet'),
        );

        return $result;
    }
}

