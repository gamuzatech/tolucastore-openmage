<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Block_Adminhtml_Cashier_Draft extends Mage_Adminhtml_Block_Template
{
    public function __construct ()
    {
        parent::__construct ();

        $this->_wordwrap = intval (Mage::getStoreConfig ('sales/order_draft/word_wrap'));
    }

    public function getLineSeparator ($title = null)
    {
        $result = str_repeat ('-', $this->_wordwrap);

        if (empty ($title))
        {
            return $result;
        }

        $title = sprintf (' %s ', $title);

        $position = strlen ($result) / 2 - strlen ($title) / 2;

        for ($i = 0; $i < strlen ($title); $i ++)
        {
            $result [$i + $position] = $title [$i];
        }

        return $result;
    }

    public function getOrderPayments ($cashier, $operator, $history)
    {
        $collection = $this->_getOrderCollection ($cashier, $operator, $history);

        $collection->getSelect ()
            ->join(
                array ('sfop' => Mage::getSingleton ('core/resource')->getTablename ('sales/order_payment')),
                'main_table.entity_id = sfop.parent_id',
                array(
                    'payment_method' => 'sfop.method',
                    'payment_count'  => 'COUNT(sfop.entity_id)',
                    'payment_amount' => 'SUM(sfop.base_amount_paid)',
                )
            )
            ->group ('sfop.method')
            ->order ('payment_count DESC')
            ->order ('payment_amount DESC')
        ;

        return $collection;
    }

    public function getOrderShipments ($cashier, $operator, $history)
    {
        $collection = $this->_getOrderCollection ($cashier, $operator, $history);

        $collection->getSelect ()
            ->columns (array(
                'shipment_count'  => 'COUNT(entity_id)',
                'shipment_amount' => 'SUM(base_shipping_amount)',
            ))
            ->group ('shipping_method')
            ->order ('shipment_count DESC')
            ->order ('shipment_amount DESC')
        ;

        return $collection;
    }

    public function getOrderProducts ($cashier, $operator, $history)
    {
        $collection = $this->_getOrderCollection ($cashier, $operator, $history);

        $collection = Mage::getModel ('sales/order_item')->getCollection ()
            ->addFieldToFilter ('order_id', array ('in' => $collection->getAllIds ()))
            ->addFieldToFilter ('parent_item_id', array ('null' => true))
        ;

        $collection->getSelect ()
            ->columns (array(
                'product_count'  => 'SUM(qty_invoiced)',
                'product_amount' => 'SUM(base_row_total)', // BUG: base_row_invoiced is NULL
            ))
            ->group ('sku')
            ->order ('product_count DESC')
            ->order ('product_amount DESC')
        ;

        return $collection;
    }

    private function _getOrderCollection ($cashier, $operator, $history)
    {
        $collection = Mage::getModel ('sales/order')->getCollection ()
            ->addFieldToFilter ('is_pdv', array ('eq' => true))
            ->addFieldToFilter ('pdv_cashier_id', array ('eq' => $cashier->getId ()))
            ->addFieldToFilter ('pdv_operator_id', array ('eq' => $operator->getId ()))
            ->addFieldToFilter ('pdv_history_id', array ('eq' => $history->getId ()))
        ;

        if (!Mage::getStoreConfigFlag ('pdv/cashier/show_pending_orders'))
        {
             $collection->addFieldToFilter ('state', array ('in' => array (
                 Mage_Sales_Model_Order::STATE_PROCESSING,
                 Mage_Sales_Model_Order::STATE_COMPLETE,
             )));
        }

        return $collection;
    }
}

