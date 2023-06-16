<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Helper_Sales_Order_Status extends Mage_Core_Helper_Abstract
{
    public function canceled ($order)
    {
        $status  = Gamuza_Basic_Model_Sales_Order::STATUS_CANCELED;
        $comment = Mage::helper ('basic')->__('The order was canceled.');

        $order->queueOrderUpdateEmail (true, $comment, true)
            ->addStatusHistoryComment ($comment, $status)
            ->setIsCustomerNotified (true)
            ->setIsVisibleOnFront (true)
            ->save ()
            ->getOrder ()
            ->save ()
        ;

        Mage::dispatchEvent ('sales_order_canceled_after', array ('order' => $order));
    }

    public function preparing ($order)
    {
        $status  = Gamuza_Basic_Model_Sales_Order::STATUS_PREPARING;
        $comment = Mage::helper ('basic')->__('The order is being prepared.');

        $order->queueOrderUpdateEmail (true, $comment, true)
            ->addStatusHistoryComment ($comment, $status)
            ->setIsCustomerNotified (true)
            ->setIsVisibleOnFront (true)
            ->save ()
            ->getOrder ()
            ->save ()
        ;

        Mage::dispatchEvent ('sales_order_preparing_after', array ('order' => $order));
    }

    public function paid ($order)
    {
        $status  = Gamuza_Basic_Model_Sales_Order::STATUS_PAID;
        $comment = Mage::helper ('basic')->__('The order was paid.');

        $order->queueOrderUpdateEmail (true, $comment, true)
            ->addStatusHistoryComment ($comment, $status)
            ->setIsCustomerNotified (true)
            ->setIsVisibleOnFront (true)
            ->save ()
            ->getOrder ()
            ->save ()
        ;

        Mage::dispatchEvent ('sales_order_paid_after', array ('order' => $order));
    }

    public function shipped ($order)
    {
        $status  = Gamuza_Basic_Model_Sales_Order::STATUS_SHIPPED;
        $comment = Mage::helper ('basic')->__('The order was shipped.');

        $order->queueOrderUpdateEmail (true, $comment, true)
            ->addStatusHistoryComment ($comment, $status)
            ->setIsCustomerNotified (true)
            ->setIsVisibleOnFront (true)
            ->save ()
            ->getOrder ()
            ->save ()
        ;

        Mage::dispatchEvent ('sales_order_shipped_after', array ('order' => $order));
    }

    public function delivered ($order)
    {
        $status  = Gamuza_Basic_Model_Sales_Order::STATUS_DELIVERED;
        $comment = $this->__('The order was delivered.');

        $order->queueOrderUpdateEmail (true, $comment, true)
            ->addStatusHistoryComment ($comment, $status)
            ->setIsCustomerNotified (true)
            ->setIsVisibleOnFront (true)
            ->save ()
            ->getOrder ()
            ->save ()
        ;

        Mage::dispatchEvent ('sales_order_delivered_after', array ('order' => $order));
    }

    public function refunded ($order)
    {
        $status  = Gamuza_Basic_Model_Sales_Order::STATUS_REFUNDED;
        $comment = Mage::helper ('basic')->__('The order was refunded.');

        $order->queueOrderUpdateEmail (true, $comment, true)
            ->addStatusHistoryComment ($comment, $status)
            ->setIsCustomerNotified (true)
            ->setIsVisibleOnFront (true)
            ->save ()
            ->getOrder ()
            ->save ()
        ;

        Mage::dispatchEvent ('sales_order_refunded_after', array ('order' => $order));
    }
}

