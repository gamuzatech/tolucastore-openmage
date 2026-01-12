<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Eav_Entity_Attribute_Source_Product_PrinterId
    extends Mage_Eav_Model_Entity_Attribute_Source_Boolean
{
    public function getAllOptions ($withEmpty = true, $defaultValues = false)
    {
        if (is_null($this->_options))
        {
            for ($i = 1; $i < 7; $i ++)
            {
                $this->_options [] = array ('value' => $i, 'label' => $i);
            }

            if ($withEmpty)
            {
                array_unshift ($this->_options, array ('value' => '', 'label' => ''));
            }
        }

        return $this->_options;
    }
}

