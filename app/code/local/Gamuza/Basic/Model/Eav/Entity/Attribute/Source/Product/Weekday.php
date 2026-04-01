<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Eav_Entity_Attribute_Source_Product_Weekday
    extends Mage_Eav_Model_Entity_Attribute_Source_Boolean
{
    public function getAllOptions ($withEmpty = true, $defaultValues = false)
    {
        if (is_null($this->_options))
        {
            $this->_options = Mage::getModel('basic/adminhtml_system_config_source_weekday')->toOptionArray();

            if ($withEmpty)
            {
                array_unshift ($this->_options, array ('value' => '', 'label' => ''));
            }
        }

        return $this->_options;
    }

    /**
     * Retrieve flat column definition
     *
     * @return array
     */
    public function getFlatColums()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();

        $column = array(
            'unsigned'  => false,
            'default'   => null,
            'extra'     => null,
        );

        if (Mage::helper('core')->useDbCompatibleMode())
        {
            $column['type']     = 'varchar(255)';
            $column['is_null']  = true;
        }
        else
        {
            $column['type']     = Varien_Db_Ddl_Table::TYPE_VARCHAR;
            $column['length']   = 255;
            $column['nullable'] = true;
            $column['comment']  = $attributeCode . ' column';
        }

        return array ($attributeCode => $column);
    }
}

