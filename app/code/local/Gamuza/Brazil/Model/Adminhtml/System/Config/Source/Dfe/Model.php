<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Dfe_Model config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Dfe_Model
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::DFE_MODEL_NFCE,   'label' => Mage::helper ('brazil')->__('NFC-e')),
            array ('value' => Gamuza_Brazil_Helper_Data::DFE_MODEL_NFE,    'label' => Mage::helper ('brazil')->__('NF-e')),
            array ('value' => Gamuza_Brazil_Helper_Data::DFE_MODEL_MDFE,   'label' => Mage::helper ('brazil')->__('MDF-e')),
            array ('value' => Gamuza_Brazil_Helper_Data::DFE_MODEL_CTE,    'label' => Mage::helper ('brazil')->__('CT-e')),
            array ('value' => Gamuza_Brazil_Helper_Data::DFE_MODEL_CTE_OS, 'label' => Mage::helper ('brazil')->__('CT-e OS')),
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
            Gamuza_Brazil_Helper_Data::DFE_MODEL_NFCE   => Mage::helper ('brazil')->__('NFC-e'),
            Gamuza_Brazil_Helper_Data::DFE_MODEL_NFE    => Mage::helper ('brazil')->__('NF-e'),
            Gamuza_Brazil_Helper_Data::DFE_MODEL_MDFE   => Mage::helper ('brazil')->__('MDF-e'),
            Gamuza_Brazil_Helper_Data::DFE_MODEL_CTE    => Mage::helper ('brazil')->__('CT-e'),
            Gamuza_Brazil_Helper_Data::DFE_MODEL_CTE_OS => Mage::helper ('brazil')->__('CT-e OS'),
        );

        return $result;
    }
}

