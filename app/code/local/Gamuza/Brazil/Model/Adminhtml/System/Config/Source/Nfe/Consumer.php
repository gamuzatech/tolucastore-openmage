<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Nfe_Consumer config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Consumer
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CONSUMER_NORMAL, 'label' => Mage::helper ('brazil')->__('Normal')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_CONSUMER_FINAL,  'label' => Mage::helper ('brazil')->__('Final')),
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
            Gamuza_Brazil_Helper_Data::NFE_CONSUMER_NORMAL => Mage::helper ('brazil')->__('Normal'),
            Gamuza_Brazil_Helper_Data::NFE_CONSUMER_FINAL  => Mage::helper ('brazil')->__('Final'),
        );

        return $result;
    }
}

