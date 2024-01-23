<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in environment options for Nfe_Environemnt config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Nfe_Environment
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_ENVIRONMENT_HOMOLOGATION, 'label' => Mage::helper ('brazil')->__('Homologation')),
            array ('value' => Gamuza_Brazil_Helper_Data::NFE_ENVIRONMENT_PRODUCTION,   'label' => Mage::helper ('brazil')->__('Production')),
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
            Gamuza_Brazil_Helper_Data::NFE_ENVIRONMENT_HOMOLOGATION => Mage::helper ('brazil')->__('Homologation'),
            Gamuza_Brazil_Helper_Data::NFE_ENVIRONMENT_PRODUCTION   => Mage::helper ('brazil')->__('Production'),
        );

        return $result;
    }
}

