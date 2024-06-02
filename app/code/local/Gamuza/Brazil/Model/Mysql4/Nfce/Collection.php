<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Brazil_Model_Mysql4_Nfce_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct ()
    {
        $this->_init ('brazil/nfce');
    }

    public function addOrderInfo ()
    {
        $this->getSelect ()
            ->joinLeft(
                array ('order' => Mage::getSingleton ('core/resource')->getTablename ('sales/order')),
                'main_table.order_id = order.entity_id',
                array(
                    'increment_id',
                    'protect_code',
                    'customer_id',
                    'customer_email',
                    'customer_firstname',
                    'customer_lastname',
                    'customer_taxvat',
                    'base_discount_amount',
                    'base_shipping_amount',
                    'base_shipping_discount_amount',
                    'payment_authorization_amount',
                    'is_pdv',
                    'pdv_cashier_id',
                    'pdv_operator_id',
                    'pdv_customer_id',
                    'pdv_history_id',
                    'pdv_sequence_id',
                    'pdv_table_id',
                )
            )
        ;

        return $this;
    }
}

