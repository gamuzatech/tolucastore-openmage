<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Ibpt_Type config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Ibpt_Type
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::IBPT_TYPE_NCM,   'label' => Mage::helper ('brazil')->__('NCM')),
            array ('value' => Gamuza_Brazil_Helper_Data::IBPT_TYPE_NBS,   'label' => Mage::helper ('brazil')->__('NBS')),
            array ('value' => Gamuza_Brazil_Helper_Data::IBPT_TYPE_LC116, 'label' => Mage::helper ('brazil')->__('LC116')),
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
            Gamuza_Brazil_Helper_Data::IBPT_TYPE_NCM   => Mage::helper ('brazil')->__('NCM'),
            Gamuza_Brazil_Helper_Data::IBPT_TYPE_NBS   => Mage::helper ('brazil')->__('NBS'),
            Gamuza_Brazil_Helper_Data::IBPT_TYPE_LC116 => Mage::helper ('brazil')->__('LC116'),
        );

        return $result;
    }
}

