<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Log_Type config value selection
 */
class Toluca_PDV_Model_Adminhtml_System_Config_Source_Log_Type
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Toluca_PDV_Helper_Data::LOG_TYPE_OPEN,      'label' => Mage::helper ('pdv')->__('Open')),
            array ('value' => Toluca_PDV_Helper_Data::LOG_TYPE_REINFORCE, 'label' => Mage::helper ('pdv')->__('Reinforce')),
            array ('value' => Toluca_PDV_Helper_Data::LOG_TYPE_BLEED,     'label' => Mage::helper ('pdv')->__('Bleed')),
            array ('value' => Toluca_PDV_Helper_Data::LOG_TYPE_MONEY,     'label' => Mage::helper ('pdv')->__('Money')),
            array ('value' => Toluca_PDV_Helper_Data::LOG_TYPE_CHANGE,    'label' => Mage::helper ('pdv')->__('MOney Change')),
            array ('value' => Toluca_PDV_Helper_Data::LOG_TYPE_ORDER,     'label' => Mage::helper ('pdv')->__('Order')),
            array ('value' => Toluca_PDV_Helper_Data::LOG_TYPE_CLOSE,     'label' => Mage::helper ('pdv')->__('Close')),
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
            Toluca_PDV_Helper_Data::LOG_TYPE_OPEN      => Mage::helper ('pdv')->__('Open'),
            Toluca_PDV_Helper_Data::LOG_TYPE_REINFORCE => Mage::helper ('pdv')->__('Reinforce'),
            Toluca_PDV_Helper_Data::LOG_TYPE_BLEED     => Mage::helper ('pdv')->__('Bleed'),
            Toluca_PDV_Helper_Data::LOG_TYPE_MONEY     => Mage::helper ('pdv')->__('Money'),
            Toluca_PDV_Helper_Data::LOG_TYPE_CHANGE    => Mage::helper ('pdv')->__('Money Change'),
            Toluca_PDV_Helper_Data::LOG_TYPE_ORDER     => Mage::helper ('pdv')->__('Order'),
            Toluca_PDV_Helper_Data::LOG_TYPE_CLOSE     => Mage::helper ('pdv')->__('Close'),
        );

        return $result;
    }
}

