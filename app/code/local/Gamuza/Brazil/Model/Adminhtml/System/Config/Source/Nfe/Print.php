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
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRINT_PORTRAIT,  'label' => Mage::helper ('brazil')->__('Portrait')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRINT_LANDSCAPE, 'label' => Mage::helper ('brazil')->__('Landscape')),
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
            Gamuza_Brazil_Helper_Data::NFE_PRINT_PORTRAIT  => Mage::helper ('brazil')->__('Portrait'),
            Gamuza_Brazil_Helper_Data::NFE_PRINT_LANDSCAPE => Mage::helper ('brazil')->__('Landscape'),
        );

        return $result;
    }
}

