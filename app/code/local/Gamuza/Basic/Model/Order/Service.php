<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Order_Service extends Mage_Sales_Model_Abstract
{
    public const STATE_OPEN       = 'open';
    public const STATE_CLOSED     = 'closed';
    public const STATE_PROCESSING = 'processing';
    public const STATE_CANCELED   = 'canceled';
    public const STATE_REFUNDED   = 'refunded';

    public const HISTORY_ENTITY_NAME = 'service';

    protected function _construct ()
    {
        $this->_init ('basic/order_service');
    }

    public function getStore()
    {
        return $this->getOrder()->getStore();
    }

    public function setOrder(Mage_Sales_Model_Order $order)
    {
        $this->_order = $order;

        $this->setOrderId($order->getId())
            ->setOrderIncrementId($order->getIncrementId())
            ->setStoreId($order->getStoreId())
            ->setCustomerId($order->getcustomerId())
            ->setPaymentMethod($order->getPayment()->getMethod())
            ->setShippingMethod($order->getShippingMethod())
            ->setShippingAmount($order->getBaseShippingAmount())
            ->setSubtotalAmount($order->getBaseSubtotal())
            ->setTotalAmount($order->getBaseGrandTotal())
        ;

        return $this;
    }

    public function getOrder()
    {
        if (!$this->_order instanceof Mage_Sales_Model_Order)
        {
            $this->_order = Mage::getModel('sales/order')->load($this->getOrderId());
        }

        return $this->_order->setHistoryEntityName(self::HISTORY_ENTITY_NAME);
    }
}

