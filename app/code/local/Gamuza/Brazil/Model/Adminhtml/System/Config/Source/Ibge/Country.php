<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Ibge_Country config value selection
 */
class Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Ibge_Country
    extends Gamuza_Brazil_Model_Adminhtml_System_Config_Source_Ibge_Abstract
{
    public function _getCollection ()
    {
        return Mage::getModel ('brazil/country')->getCollection ();
    }
}

