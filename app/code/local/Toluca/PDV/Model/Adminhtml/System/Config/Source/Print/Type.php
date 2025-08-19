<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Print_Type config value selection
 */
class Toluca_PDV_Model_Adminhtml_System_Config_Source_Print_Type
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Toluca_PDV_Helper_Data::PRINT_TYPE_CASHIER, 'label' => Mage::helper ('pdv')->__('Cashier')),
            array ('value' => Toluca_PDV_Helper_Data::PRINT_TYPE_KITCHEN, 'label' => Mage::helper ('pdv')->__('Kitchen')),
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
            Toluca_PDV_Helper_Data::PRINT_TYPE_CASHIER => Mage::helper ('pdv')->__('Cashier'),
            Toluca_PDV_Helper_Data::PRINT_TYPE_KITCHEN => Mage::helper ('pdv')->__('Kitchen'),
        );

        return $result;
    }
}

