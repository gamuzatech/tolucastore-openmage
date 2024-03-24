<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Presence config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Presence
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRESENCE_NONE,        'label' => Mage::helper ('brazil')->__('None')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRESENCE_LOCAL,       'label' => Mage::helper ('brazil')->__('Local')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRESENCE_INTERNET,    'label' => Mage::helper ('brazil')->__('Internet')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRESENCE_TELESERVICE, 'label' => Mage::helper ('brazil')->__('Teleservice')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRESENCE_DELIVERY,    'label' => Mage::helper ('brazil')->__('Delivery')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRESENCE_EXTERNAL,    'label' => Mage::helper ('brazil')->__('External')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_PRESENCE_OTHER,       'label' => Mage::helper ('brazil')->__('Other')),
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
            Gamuza_Brazil_Helper_Data::NFE_PRESENCE_NONE        => Mage::helper ('brazil')->__('None'),
            Gamuza_Brazil_Helper_Data::NFE_PRESENCE_LOCAL       => Mage::helper ('brazil')->__('Local'),
            Gamuza_Brazil_Helper_Data::NFE_PRESENCE_INTERNET    => Mage::helper ('brazil')->__('Internet'),
            Gamuza_Brazil_Helper_Data::NFE_PRESENCE_TELESERVICE => Mage::helper ('brazil')->__('Teleservice'),
            Gamuza_Brazil_Helper_Data::NFE_PRESENCE_DELIVERY    => Mage::helper ('brazil')->__('Delivery'),
            Gamuza_Brazil_Helper_Data::NFE_PRESENCE_EXTERNAL    => Mage::helper ('brazil')->__('External'),
            Gamuza_Brazil_Helper_Data::NFE_PRESENCE_OTHER       => Mage::helper ('brazil')->__('Other'),
        );

        return $result;
    }
}

