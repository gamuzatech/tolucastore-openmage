<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Password length config field backend model
 */
class Gamuza_Basic_Model_Adminhtml_System_Config_Backend_Passwordlength
    extends Mage_Adminhtml_Model_System_Config_Backend_Passwordlength
{
    public const ABSOLUTE_MIN_PASSWORD_LENGTH = 6;

    /**
     * Before save processing
     *
     * @throws Mage_Core_Exception
     * @return Mage_Adminhtml_Model_System_Config_Backend_Passwordlength
     */
    protected function _beforeSave()
    {
        if ((int)$this->getValue() < self::ABSOLUTE_MIN_PASSWORD_LENGTH)
        {
            Mage::throwException(Mage::helper('adminhtml')
                ->__('Password must be at least of %d characters.', self::ABSOLUTE_MIN_PASSWORD_LENGTH));
        }

        return $this;
    }
}

