<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Bundle Price Type Attribute Renderer
 */
class Gamuza_Basic_Model_Bundle_Product_Attribute_Source_Price_Type
    extends Mage_Bundle_Model_Product_Attribute_Source_Price_View
{
    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (is_null($this->_options))
        {
            $this->_options = array(
                array(
                    'label' => Mage::helper('bundle')->__('Dynamic'),
                    'value' => Mage_Bundle_Model_Product_Price::PRICE_TYPE_DYNAMIC
                ),
                array(
                    'label' => Mage::helper('bundle')->__('Fixed'),
                    'value' => Mage_Bundle_Model_Product_Price::PRICE_TYPE_FIXED
                ),
            );
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
            'extra'     => null
        );

        if (Mage::helper('core')->useDbCompatibleMode())
        {
            $column['type']     = 'int';
            $column['is_null']  = true;
        }
        else
        {
            $column['type']     = Varien_Db_Ddl_Table::TYPE_INTEGER;
            $column['nullable'] = true;
            $column['comment']  = 'Bundle Price Type ' . $attributeCode . ' column';
        }

        return array($attributeCode => $column);
   }
}

