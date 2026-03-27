<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Mobile_Block_Adminhtml_Customer_Draft extends Mage_Adminhtml_Block_Template
{
    protected $_separator = '-'; // default

    private $_fontgrow = 0;  // default
    private $_wordwrap = 48; // default

    public function __construct ()
    {
        parent::__construct ();

        $this->_fontgrow = Mage::getStoreConfigFlag ('customer/customer_draft/font_grow');
        $this->_wordwrap = Mage::getStoreConfigAsInt ('customer/customer_draft/word_wrap');
    }

    public function getFontGrow ()
    {
        return $this->_fontgrow;
    }

    public function getWordWrap ()
    {
        return $this->_wordwrap;
    }

    public function getLineSeparator ($title = null)
    {
        $result = str_repeat ($this->_separator, $this->_wordwrap);

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

    public function getOrdersList ()
    {
        $collection = Mage::getModel ('sales/order')->getCollection ()
            ->addFieldToFilter ('state', array ('eq' => Mage_Sales_Model_Order::STATE_NEW))
        ;

        if (is_array ($this->getOrderIds ()) && count ($this->getOrderIds ()) > 0)
        {
            $collection->addFieldToFilter ('entity_id', array ('in' => $this->getOrderIds ()));
        }

        $customerId = $this->getCustomer ()->getId ();

        $condition = sprintf ('customer_id = %s', $customerId);

        if (Mage::helper ('core')->isModuleEnabled ('Toluca_PDV'))
        {
            $condition .= sprintf (' OR pdv_customer_id = %s ', $customerId);
        }

        $collection->getSelect ()
            ->join (
                array ('sfop' => Mage::getSingleton ('core/resource')->getTableName ('sales/order_payment')),
                'main_table.entity_id = sfop.parent_id',
                array (
                    'deferred_fee_percentage',
                    'deferred_fee_amount',
                )
            )
            ->where ($condition)
            ->order ('entity_id DESC')
        ;

        return $collection;
    }

    public function getOrdersTotal ($orders, $field)
    {
        $result = array_sum (array_map (function ($_order) use ($field) {
            return $_order->getData ($field);
        }, $orders->getItems ()));

        return $result;
    }

    public function getOrderNumber ($order)
    {
        return sprintf ('%s', $order->getRealOrderId () ?? $order->getId ());
    }

    public function getOrderDatetime ($order)
    {
        return Mage::helper ('core')->formatDate ($order->getCreatedAtDate (), 'medium', true);
    }

    public function getOrderSequence ($order)
    {
        $result = null;

        if (Mage::helper ('core')->isModuleEnabled ('Toluca_PDV'))
        {
            $result = $order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_SEQUENCE_ID);
        }

        return $result ?? $order->getId ();
    }
}

