<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Eav_Entity_Attribute_Source_Product_Printing
    extends Mage_Eav_Model_Entity_Attribute_Source_Boolean
{
    /**
     * Option values
     */
    public const VALUE_NO  = '2';
    public const VALUE_YES = '1';

    public function getAllOptions ($withEmpty = true, $defaultValues = false)
    {
        if (is_null($this->_options))
        {
            $this->_options = array(
                array(
                    'label' => Mage::helper ('pdv')->__('No'),
                    'value' => self::VALUE_NO,
                ),
                array(
                    'label' => Mage::helper ('pdv')->__('Yes'),
                    'value' => self::VALUE_YES,
                ),
            );

            if ($withEmpty)
            {
                array_unshift ($this->_options, array ('value' => '', 'label' => ''));
            }
        }

        return $this->_options;
    }
}

