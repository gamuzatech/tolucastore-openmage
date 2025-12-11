<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * PDV Comanda API
 */
class Toluca_PDV_Model_Comanda_Api extends Mage_Api_Model_Resource_Abstract
{
    public function notify ($card_id, $table_id, $type_id)
    {
        if (!$card_id && !$table_id)
        {
            $card_id = -1;
            $table_id = -1;
        }

        $collection = Mage::getModel ('sales/quote')->getCollection ()
            ->addFieldToFilter ('pdv_card_id', array ('eq' => $card_id))
            ->addFieldToFilter ('pdv_table_id', array ('eq' => $table_id))
        ;

        $quote = $collection->getFirstItem ();

        if ($quote && $quote->getId ())
        {
            $quote->setData ('is_comanda_'. $type_id, true)->save ();

            return $quote->getData ('is_comanda_' . $type_id);
        }

        return false;
    }

    public function resolve ($card_id, $table_id)
    {
        if (!$card_id && !$table_id)
        {
            $card_id = -1;
            $table_id = -1;
        }

        $collection = Mage::getModel ('sales/quote')->getCollection ()
            ->addFieldToFilter ('pdv_card_id', array ('eq' => $card_id))
            ->addFieldToFilter ('pdv_table_id', array ('eq' => $table_id))
        ;

        $quote = $collection->getFirstItem ();

        if ($quote && $quote->getId ())
        {
            $quote->setData ('is_comanda_alert', false)
                ->setData ('is_comanda_bill', false)
                ->save ()
            ;

            return true;
        }

        return false;
    }
}

