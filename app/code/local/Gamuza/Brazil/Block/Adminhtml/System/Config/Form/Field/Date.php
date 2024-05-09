<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Adminhtml system config date field renderer
 */
class Gamuza_Brazil_Block_Adminhtml_System_Config_Form_Field_Date
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Datetime
{
    protected function _getElementHtml (Varien_Data_Form_Element_Abstract $element)
    {
        $value = $element->getValue ();

        if (empty ($value))
        {
            return null;
        }

        $locale = new Zend_Locale ();

        return Mage::app ()->getLocale ()->date ($value, null, $locale, false)->toString ('dd/MM/YYYY');
    }
}

