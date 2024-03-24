<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Process config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Process
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PROCESS_PDV,        'label' => Mage::helper ('brazil')->__('PDV')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PROCESS_FISCO,      'label' => Mage::helper ('brazil')->__('Fisco')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PROCESS_FISCO_SITE, 'label' => Mage::helper ('brazil')->__('Fisco Site')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PROCESS_FISCO_PDV,  'label' => Mage::helper ('brazil')->__('Fisco PDV')),
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
            Gamuza_Brazil_Helper_Data::NFE_PROCESS_PDV        => Mage::helper ('brazil')->__('PDV'),
            Gamuza_Brazil_Helper_Data::NFE_PROCESS_FISCO      => Mage::helper ('brazil')->__('Fisco'),
            Gamuza_Brazil_Helper_Data::NFE_PROCESS_FISCO_SITE => Mage::helper ('brazil')->__('Fisco Site'),
            Gamuza_Brazil_Helper_Data::NFE_PROCESS_FISCO_PDV  => Mage::helper ('brazil')->__('Fisco PDV'),
        );

        return $result;
    }
}

