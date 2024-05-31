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

        $defaultLocale = Mage::getStoreConfig (Mage_Core_Model_Locale::XML_PATH_DEFAULT_LOCALE);

        $locale = new Zend_Locale ($defaultLocale);

        return Mage::app ()->getLocale ()->date ($value, null, $locale, true)->toString ('dd/MM/YYYY HH:mm:ss');
    }
}

