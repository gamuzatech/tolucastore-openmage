<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Customer IE / ICMS attribute source
 */
class Gamuza_Brazil_Model_Eav_Entity_Attribute_Source_Ie_Icms
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Retrieve Full Option values array
     *
     * @return array
     */
    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        if ($this->_options === null)
        {
            $this->_options = array(
                array ('value' => null, 'label' => Mage::helper ('brazil')->__('-- Please Select --')),
                array ('value' => Gamuza_Brazil_Helper_Data::NFE_CUSTOMER_IE_ICMS, 'label' => Mage::helper ('brazil')->__('ICMS Taxpayer')),
                array ('value' => Gamuza_Brazil_Helper_Data::NFE_CUSTOMER_IE_FREE, 'label' => Mage::helper ('brazil')->__('ICMS Free')),
                array ('value' => Gamuza_Brazil_Helper_Data::NFE_CUSTOMER_IE_NONE, 'label' => Mage::helper ('brazil')->__('ICMS None')),
            );
        }

        return $this->_options;
    }
}

