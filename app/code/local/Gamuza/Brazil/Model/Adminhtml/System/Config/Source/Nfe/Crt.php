<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Crt config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Crt
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CRT_SIMPLE_NATIONAL,        'label' => Mage::helper ('brazil')->__('Simple National')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CRT_SIMPLE_NATIONAL_EXCESS, 'label' => Mage::helper ('brazil')->__('Simple National Excess')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CRT_NORMAL_REGIME,          'label' => Mage::helper ('brazil')->__('Normal Regime')),
            /*
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CRT_SIMPLE_NATIONAL_MEI,    'label' => Mage::helper ('brazil')->__('Simple National MEI')),
            */
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
            Gamuza_Brazil_Helper_Data::NFE_CRT_SIMPLE_NATIONAL        => Mage::helper ('brazil')->__('Simple National'),
            Gamuza_Brazil_Helper_Data::NFE_CRT_SIMPLE_NATIONAL_EXCESS => Mage::helper ('brazil')->__('Simple National Excess'),
            Gamuza_Brazil_Helper_Data::NFE_CRT_NORMAL_REGIME          => Mage::helper ('brazil')->__('Normal Regime'),
            /*
            Gamuza_Brazil_Helper_Data::NFE_CRT_SIMPLE_NATIONAL_MEI    => Mage::helper ('brazil')->__('Simple National MEI'),
            */
        );

        return $result;
    }
}

