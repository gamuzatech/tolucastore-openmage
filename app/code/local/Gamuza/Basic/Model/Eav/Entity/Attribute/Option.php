<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Emtity attribute option model
 */
class Gamuza_Basic_Model_Eav_Entity_Attribute_Option
    extends Mage_Eav_Model_Entity_Attribute_Option
{
    protected function _afterLoad ()
    {
        parent::_afterLoad ();

        $item = $this->_getStoreValue ();

        if ($item && $item->getId ())
        {
            $this->setStoreId ($item->getStoreId ());
            $this->setvalue ($item->getValue ());
        }
    }

    protected function _afterSave ()
    {
        parent::_afterSave ();

        $item = $this->_getStoreValue ()
            ->setOptionId ($this->getId ())
            ->setStoreId ($this->getStoreId ())
            ->setValue ($this->getValue ())
            ->save ()
        ;

        return $this;
    }

    protected function _afterDelete ()
    {
        parent::_afterDelete ();

        $collection = Mage::getModel ('basic/eav_entity_attribute_option_value')->getCollection ()
            ->addFieldToFilter ('option_id', array ('eq' => $this->getId ()))
        ;

        foreach ($collection as $value)
        {
            $value->delete ();
        }
    }

    private function _getStoreValue ()
    {
        $collection = Mage::getModel ('basic/eav_entity_attribute_option_value')->getCollection ()
            ->addFieldToFilter ('option_id', array ('eq' => $this->getId ()))
            ->addFieldToFilter ('store_id', array ('eq' => Mage_Core_Model_App::ADMIN_STORE_ID))
        ;

        return $collection->getFirstItem ();
    }
}

