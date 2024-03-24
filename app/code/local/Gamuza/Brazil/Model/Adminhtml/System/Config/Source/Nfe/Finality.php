<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Finality config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Finality
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_FINALITY_NORMAL,        'label' => Mage::helper ('brazil')->__('Normal')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_FINALITY_COMPLEMENTARY, 'label' => Mage::helper ('brazil')->__('Complementary')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_FINALITY_ADJUSTMENT,    'label' => Mage::helper ('brazil')->__('Adjustment')),
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
            Gamuza_Brazil_Helper_Data::NFE_FINALITY_NORMAL        => Mage::helper ('brazil')->__('Normal'),
            Gamuza_Brazil_Helper_Data::NFE_FINALITY_COMPLEMENTARY => Mage::helper ('brazil')->__('Complementary'),
            Gamuza_Brazil_Helper_Data::NFE_FINALITY_ADJUSTMENT    => Mage::helper ('brazil')->__('Adjustment'),
        );

        return $result;
    }
}

