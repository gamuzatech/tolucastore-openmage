<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Emission config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Emission
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_EMISSION_NORMAL,             'label' => Mage::helper ('brazil')->__('Normal')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_EMISSION_CONTINGENCY_FS,     'label' => Mage::helper ('brazil')->__('Contingency FS')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_EMISSION_CONTINGENCY_SCAN,   'label' => Mage::helper ('brazil')->__('Contingency SCAN')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_EMISSION_CONTINGENCY_DPEC,   'label' => Mage::helper ('brazil')->__('Contingency DPEC')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_EMISSION_CONTINGENCY_FS_DA,  'label' => Mage::helper ('brazil')->__('Contingency FS-DA')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_EMISSION_CONTINGENCY_SVC_AN, 'label' => Mage::helper ('brazil')->__('Contingency SVC-AN')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_EMISSION_CONTINGENCY_SVC_RS, 'label' => Mage::helper ('brazil')->__('Contingency SVC-RS')),
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
            Gamuza_Brazil_Helper_Data::NFE_EMISSION_NORMAL             => Mage::helper ('brazil')->__('Normal'),
            Gamuza_Brazil_Helper_Data::NFE_EMISSION_CONTINGENCY_FS     => Mage::helper ('brazil')->__('Contingency FS'),
            Gamuza_Brazil_Helper_Data::NFE_EMISSION_CONTINGENCY_SCAN   => Mage::helper ('brazil')->__('Contingency SCAN'),
            Gamuza_Brazil_Helper_Data::NFE_EMISSION_CONTINGENCY_DPEC   => Mage::helper ('brazil')->__('Contingency DPEC'),
            Gamuza_Brazil_Helper_Data::NFE_EMISSION_CONTINGENCY_FS_DA  => Mage::helper ('brazil')->__('Contingency FS-DA'),
            Gamuza_Brazil_Helper_Data::NFE_EMISSION_CONTINGENCY_SVC_AN => Mage::helper ('brazil')->__('Contingency SVC-AN'),
            Gamuza_Brazil_Helper_Data::NFE_EMISSION_CONTINGENCY_SVC_RS => Mage::helper ('brazil')->__('Contingency SVC-RS'),
        );

        return $result;
    }
}

