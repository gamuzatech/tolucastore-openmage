<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Emtity attribute option VALUE model
 */
class Gamuza_Basic_Model_Eav_Entity_Attribute_Option_Value extends Mage_Core_Model_Abstract
{
    /**
     * Resource initialization
     */
    public function _construct()
    {
        $this->_init ('basic/eav_entity_attribute_option_value');
    }
}

