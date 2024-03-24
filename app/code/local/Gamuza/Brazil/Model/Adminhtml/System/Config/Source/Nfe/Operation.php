<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Operation config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Operation
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_OPERATION_INPUT, 'label' => Mage::helper ('brazil')->__('Input')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_OPERATION_OUTPUT, 'label' => Mage::helper ('brazil')->__('Output')),
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
            Gamuza_Brazil_Helper_Data::NFE_OPERATION_INPUT => Mage::helper ('brazil')->__('Input'),
            Gamuza_Brazil_Helper_Data::NFE_OPERATION_OUTPUT => Mage::helper ('brazil')->__('Output'),
        );

        return $result;
    }
}

