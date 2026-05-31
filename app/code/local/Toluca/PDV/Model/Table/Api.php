<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Table API
 */
class Toluca_PDV_Model_Table_Api extends Mage_Api_Model_Resource_Abstract
{
    public function move ($table_id, $quote_id, $new_id)
    {
        if (empty ($table_id) || empty ($new_id))
        {
            $this->_fault ('table_not_specified');
        }

        if (empty ($quote_id))
        {
            $this->_fault ('quote_not_specified');
        }

        /**
         * Old
         */
        $collection = Mage::getModel ('sales/quote')->getCollection ()
            ->addFieldToFilter ('entity_id',   array ('eq' => $quote_id))
            ->addFieldToFilter ('pdv_table_id', array ('eq' => $table_id))
        ;

        $quote = $collection->getFirstItem ();

        if (!$quote || !$quote->getId ())
        {
            $this->_fault ('table_not_exists');
        }

        /**
         * New
         */
        $collection = Mage::getModel ('sales/quote')->getCollection ()
            ->addFieldToFilter ('pdv_table_id', array ('eq' => $new_id))
        ;

        $cart = $collection->getFirstItem ();

        if ($cart && $cart->getId ())
        {
            $this->_fault ('table_already_exists');
        }

        $quote->setData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_TABLE_ID, $new_id)->save ();

        return true;
    }

    public function remove ($table_id, $quote_id)
    {
        if (empty ($table_id))
        {
            $this->_fault ('table_not_specified');
        }

        if (empty ($quote_id))
        {
            $this->_fault ('quote_not_specified');
        }

        $collection = Mage::getModel ('sales/quote')->getCollection ()
            ->addFieldToFilter ('entity_id',   array ('eq' => $quote_id))
            ->addFieldToFilter ('pdv_table_id', array ('eq' => $table_id))
        ;

        $quote = $collection->getFirstItem ();

        if (!$quote || !$quote->getId ())
        {
            $this->_fault ('table_not_exists');
        }

        if (count ($quote->getAllItems ()) > 0)
        {
            $this->_fault ('table_not_empty');
        }

        $quote->delete ();

        return true;
    }
}

