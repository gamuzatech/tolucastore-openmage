<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Model_Catalog_Product_Attribute_Source_Abstract
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions ($withEmpty = true, $defaultValues = false)
    {
        if ($this->_options === null)
        {
            $this->_options = array (
                array ('value' => 0, 'label' => Mage::helper ('core')->__('-- Please Select --')),
            );

            if (!Mage::getStoreConfigFlag (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_SETTING_ACTIVE))
            {
                return $this->_options;
            }

            $collection = $this->_getCollection ();

            foreach ($collection as $item)
            {
                $this->_options [] = array ('value' => $item->getCode (), 'label' => sprintf ('%s %s', $item->getCode (), $item->getDescription ()));
            }
        }

        return $this->_options;
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

        if (Mage::helper('core')->useDbCompatibleMode())
        {
            $columns[$attributeCode] = [
                'type'      => 'varchar',
                'length'    => 255,
                'is_null'   => true,
                'default'   => null,
                'extra'     => null
            ];
            $columns[$attributeCode . '_value'] = [
                'type'      => 'varchar(255)',
                'is_null'   => true,
                'default'   => null,
                'extra'     => null
            ];
        }
        else
        {
            $columns[$attributeCode] = [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 255,
                'nullable'   => true,
                'default'   => null,
                'extra'     => null,
                'comment'   => $attributeCode . ' column'
            ];
            $columns[$attributeCode . '_value'] = [
                'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
                'length'    => 255,
                'nullable'  => true,
                'default'   => null,
                'extra'     => null,
                'comment'   => $attributeCode . ' column'
            ];
        }

        return $columns;
    }

    /**
     * Retrieve Select For Flat Attribute update
     *
     * @param int $store
     * @return Varien_Db_Select|null
     */
    public function getFlatUpdateSelect($store)
    {
        return Mage::getResourceModel('eav/entity_attribute_option')
            ->getFlatUpdateSelect($this->getAttribute(), $store, false);
    }
}

