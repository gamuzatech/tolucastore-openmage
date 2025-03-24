<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Offer_Type config value selection
 */
class Gamuza_Basic_Model_Adminhtml_System_Config_Source_Offer_Type
    extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => "", 'label' => Mage::helper ('basic')->__('-- Please Select --')),
            array ('value' => Gamuza_Basic_Helper_Data::OFFER_TYPE_CLEARANCE, 'label' => Mage::helper ('basic')->__('Clearance')),
            array ('value' => Gamuza_Basic_Helper_Data::OFFER_TYPE_LIGHTNING, 'label' => Mage::helper ('basic')->__('Lightning')),
        );

        return $result;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $result = array(
            "" => Mage::helper ('basic')->__('-- Please Select --'),
            Gamuza_Basic_Helper_Data::OFFER_TYPE_CLEARANCE => Mage::helper ('basic')->__('Clearance'),
            Gamuza_Basic_Helper_Data::OFFER_TYPE_LIGHTNING => Mage::helper ('basic')->__('Lightning'),
        );

        return $result;
    }

    /**
     * Retrieve Full Option values array
     *
     * @param bool $withEmpty       Add empty option to array
     * @param bool $defaultValues
     * @return array
     */
    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        return self::toOptionArray();
    }

    /**
     * Retrieve Column(s) for Flat
     *
     * @return array
     */
    public function getFlatColums()
    {
        $columns = [];
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $isMulti = $this->getAttribute()->getFrontend()->getInputType() == 'multiselect';

        if (Mage::helper('core')->useDbCompatibleMode())
        {
            $columns[$attributeCode] = array(
                'type'      => $isMulti ? 'text' : 'varchar(255)',
                'unsigned'  => false,
                'is_null'   => true,
                'default'   => null,
                'extra'     => null,
            );

            if (!$isMulti)
            {
                $columns[$attributeCode . '_value'] = array(
                    'type'      => 'varchar(255)',
                    'unsigned'  => false,
                    'is_null'   => true,
                    'default'   => null,
                    'extra'     => null,
                );
            }
        }
        else
        {
            $type = ($isMulti) ? Varien_Db_Ddl_Table::TYPE_TEXT : Varien_Db_Ddl_Table::TYPE_INTEGER;

            $columns[$attributeCode] = array(
                'type'      => $isMulti ? $type : Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => $isMulti ? '65535' : 255,
                'unsigned'  => false,
                'nullable'  => true,
                'default'   => null,
                'extra'     => null,
                'comment'   => $attributeCode . ' column',
            );

            if (!$isMulti)
            {
                $columns[$attributeCode . '_value'] = array(
                    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                    'length'    => 255,
                    'unsigned'  => false,
                    'nullable'  => true,
                    'default'   => null,
                    'extra'     => null,
                    'comment'   => $attributeCode . ' column',
                );
            }
        }

        return $columns;
    }
}

