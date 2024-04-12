<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Catalog Product Mass processing resource model
 */
class Gamuza_Basic_Model_Catalog_Resource_Product_Action
    extends Mage_Catalog_Model_Resource_Product_Action
{
    /**
     * Intialize connection
     *
     */
    protected function _construct ()
    {
        parent::_construct ();

        $this->_entityTypeId = Mage::getSingleton ('eav/config')
            ->getEntityType ($this->getType ())
            ->getId ()
        ;
    }

    /**
     * Insert or Update attribute data
     *
     * @param Mage_Catalog_Model_Abstract $object
     * @param Mage_Eav_Model_Entity_Attribute_Abstract|Mage_Catalog_Model_Resource_Eav_Attribute $attribute
     * @param mixed $value
     * @return Mage_Catalog_Model_Resource_Abstract
     */
    protected function _saveAttributeValue ($object, $attribute, $value)
    {
        if ($attribute->getEntityTypeId () == $this->_entityTypeId
            && !strcmp ($attribute->getAttributeCode (), 'price')
            && (str_starts_with ($value, '+') || str_starts_with ($value, '-')))
        {
            $product = Mage::getModel ('catalog/product')->getCollection ()
                ->addIdFilter ($object->getEntityId ())
                ->addStoreFilter ($object->getStoreId ())
                ->addAttributeToSelect ('price', 'left')
                ->getFirstItem ();
            ;

            if ($product && $product->getId ())
            {
                $value = $product->getPrice () + $value;
            }
        }

        return parent::_saveAttributeValue ($object, $attribute, $value);
    }
}

