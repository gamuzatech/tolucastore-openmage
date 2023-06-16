<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies. (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Block to render customer address's company attribute
 */
class Gamuza_Basic_Block_Customer_Widget_Company extends Mage_Customer_Block_Widget_Abstract
{
    /**
     * Check if company attribute enabled in system
     *
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)$this->_getAttribute('company')->getIsVisible();
    }

    /**
     * Check if company attribute marked as required
     *
     * @return bool
     */
    public function isRequired()
    {
        return (bool)$this->_getAttribute('company')->getIsRequired();
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

