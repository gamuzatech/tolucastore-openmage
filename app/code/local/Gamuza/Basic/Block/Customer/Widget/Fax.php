<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies. (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Block to render customer address's fax attribute
 */
class Gamuza_Basic_Block_Customer_Widget_Fax extends Mage_Customer_Block_Widget_Abstract
{
    /**
     * Check if fax attribute enabled in system
     *
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)$this->_getAttribute('fax')->getIsVisible();
    }

    /**
     * Check if fax attribute marked as required
     *
     * @return bool
     */
    public function isRequired()
    {
        return (bool)$this->_getAttribute('fax')->getIsRequired();
    }

    /**
     * Retrieve customer address attribute instance
     *
     * @param string $attributeCode
     * @return Mage_Customer_Model_Attribute|false
     */
    protected function _getAttribute($attributeCode)
    {
        return Mage::getSingleton('eav/config')->getAttribute('customer_address', $attributeCode);
    }
}

