<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Card_Read_Type config value selection
 */
class Gamuza_Sitef_Model_Adminhtml_System_Config_Source_Card_Read_Type
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array('value' => Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_MAGNETIC,                       'label' => Mage::helper('sitef')->__('Magnetic')),
            array('value' => Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_VISA_CASH_TIBC_V1,              'label' => Mage::helper('sitef')->__('VISA Cash TIBC v1')),
            array('value' => Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_VISA_CASH_TIBC_V3,              'label' => Mage::helper('sitef')->__('VISA Cash TIBC v3')),
            array('value' => Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_EMV_CONTACT,                    'label' => Mage::helper('sitef')->__('EMV Contact')),
            array('value' => Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_EASY_ENTRY_TIBC_V1,             'label' => Mage::helper('sitef')->__('Easy Entry TIBC v1')),
            array('value' => Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_CONTACTLESS_MAGNETIC_EMULATION, 'label' => Mage::helper('sitef')->__('Contactless Magnetic Emulation')),
            array('value' => Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_EMV_CONTACTLESS,                'label' => Mage::helper('sitef')->__('EMV Contactless')),
            array('value' => Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_MANUAL,                         'label' => Mage::helper('sitef')->__('Manual')),
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
            Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_MAGNETIC                       => Mage::helper('sitef')->__('Magnetic'),
            Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_VISA_CASH_TIBC_V1              => Mage::helper('sitef')->__('VISA Cash TIBC v1'),
            Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_VISA_CASH_TIBC_V3              => Mage::helper('sitef')->__('VISA Cash TIBC v3'),
            Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_EMV_CONTACT                    => Mage::helper('sitef')->__('EMV Contact'),
            Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_EASY_ENTRY_TIBC_V1             => Mage::helper('sitef')->__('Easy Entry TIBC v1'),
            Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_CONTACTLESS_MAGNETIC_EMULATION => Mage::helper('sitef')->__('Contactless Magnetic Emulation'),
            Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_EMV_CONTACTLESS                => Mage::helper('sitef')->__('EMV Contactless'),
            Gamuza_Sitef_Helper_Data::API_CARD_READ_TYPE_MANUAL                         => Mage::helper('sitef')->__('Manual'),
        );

        return $result;
    }
}

