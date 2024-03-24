<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Freight config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Freight
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_FREIGHT_EMITTER,  'label' => Mage::helper ('brazil')->__('Emitter')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_FREIGHT_RECEIVER, 'label' => Mage::helper ('brazil')->__('Receiver')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_FREIGHT_THIRD,    'label' => Mage::helper ('brazil')->__('Third')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_FREIGHT_NONE,     'label' => Mage::helper ('brazil')->__('None')),
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
            Gamuza_Brazil_Helper_Data::NFE_FREIGHT_EMITTER  => Mage::helper ('brazil')->__('Emitter'),
            Gamuza_Brazil_Helper_Data::NFE_FREIGHT_RECEIVER => Mage::helper ('brazil')->__('Receiver'),
            Gamuza_Brazil_Helper_Data::NFE_FREIGHT_THIRD    => Mage::helper ('brazil')->__('Third'),
            Gamuza_Brazil_Helper_Data::NFE_FREIGHT_NONE     => Mage::helper ('brazil')->__('None'),
        );

        return $result;
    }
}

