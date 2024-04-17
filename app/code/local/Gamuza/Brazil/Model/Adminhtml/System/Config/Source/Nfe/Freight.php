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
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_FREIGHT_EMITTER_CIF,  'label' => Mage::helper ('brazil')->__('Emitter CIF')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_FREIGHT_RECEIVER_FOB, 'label' => Mage::helper ('brazil')->__('Receiver FOB')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_FREIGHT_THIRD,        'label' => Mage::helper ('brazil')->__('Third')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_FREIGHT_OWN_EMITTER,  'label' => Mage::helper ('brazil')->__('Own Emitter')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_FREIGHT_OWN_RECEIVER, 'label' => Mage::helper ('brazil')->__('Own Receiver')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_FREIGHT_NONE,         'label' => Mage::helper ('brazil')->__('None')),
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
            Gamuza_Brazil_Helper_Data::NFE_FREIGHT_EMITTER_CIF  => Mage::helper ('brazil')->__('Emitter CIF'),
            Gamuza_Brazil_Helper_Data::NFE_FREIGHT_RECEIVER_FOB => Mage::helper ('brazil')->__('Receiver COF'),
            Gamuza_Brazil_Helper_Data::NFE_FREIGHT_THIRD        => Mage::helper ('brazil')->__('Third'),
            Gamuza_Brazil_Helper_Data::NFE_FREIGHT_OWN_EMITTER  => Mage::helper ('brazil')->__('Own Emitter'),
            Gamuza_Brazil_Helper_Data::NFE_FREIGHT_OWN_RECEIVER => Mage::helper ('brazil')->__('Own Receiver'),
            Gamuza_Brazil_Helper_Data::NFE_FREIGHT_NONE         => Mage::helper ('brazil')->__('None'),
        );

        return $result;
    }
}

