<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Cashier_Status config value selection
 */
class Toluca_PDV_Model_Adminhtml_System_Config_Source_Cashier_Status
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Toluca_PDV_Helper_Data::CASHIER_STATUS_CLOSED, 'label' => Mage::helper ('pdv')->__('Closed')),
            array ('value' => Toluca_PDV_Helper_Data::CASHIER_STATUS_OPENED, 'label' => Mage::helper ('pdv')->__('Opened')),
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
            Toluca_PDV_Helper_Data::CASHIER_STATUS_CLOSED => Mage::helper ('pdv')->__('Closed'),
            Toluca_PDV_Helper_Data::CASHIER_STATUS_OPENED => Mage::helper ('pdv')->__('Opened'),
        );

        return $result;
    }
}

