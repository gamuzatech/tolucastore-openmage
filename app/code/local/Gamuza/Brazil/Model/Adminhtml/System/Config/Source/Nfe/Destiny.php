<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Destiny config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Destiny
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_DESTINY_INTERNAL,   'label' => Mage::helper ('brazil')->__('Internal')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_DESTINY_INTERSTATE, 'label' => Mage::helper ('brazil')->__('Interstate')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_DESTINY_ABROAD,     'label' => Mage::helper ('brazil')->__('Abroad')),
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
            Gamuza_Brazil_Helper_Data::NFE_DESTINY_INTERNAL   => Mage::helper ('brazil')->__('Internal'),
            Gamuza_Brazil_Helper_Data::NFE_DESTINY_INTERSTATE => Mage::helper ('brazil')->__('Interstate'),
            Gamuza_Brazil_Helper_Data::NFE_DESTINY_ABROAD     => Mage::helper ('brazil')->__('Abroad'),
        );

        return $result;
    }
}

