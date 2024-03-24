<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Intermediary config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Intermediary
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_INTERMEDIARY_OWN,   'label' => Mage::helper ('brazil')->__('Own')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_INTERMEDIARY_THIRD, 'label' => Mage::helper ('brazil')->__('Third')),
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
            Gamuza_Brazil_Helper_Data::NFE_INTERMEDIARY_OWN   => Mage::helper ('brazil')->__('Own'),
            Gamuza_Brazil_Helper_Data::NFE_INTERMEDIARY_THIRD => Mage::helper ('brazil')->__('Third'),
        );

        return $result;
    }
}

