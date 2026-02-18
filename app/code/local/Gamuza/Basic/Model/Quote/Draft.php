<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Quote_Draft extends Mage_Sales_Model_Abstract
{
    public const STATE_OPEN       = 'open';
    public const STATE_CLOSED     = 'closed';
    public const STATE_PROCESSING = 'processing';
    public const STATE_CANCELED   = 'canceled';
    public const STATE_REFUNDED   = 'refunded';

    public const HISTORY_ENTITY_NAME = 'quote_draft';

    protected function _construct ()
    {
        $this->_init ('basic/quote_draft');
    }

    public function getStore()
    {
        if ($this->getOrder())
        {
            return $this->getOrder()->getStore();
        }

        return $this->getQuote()->getStore();
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

        if (!$this->_order || !$this->_order->getId())
        {
            $converter = Mage::getModel('sales/convert_quote');

            $quote = $this->getQuote();

            $order = $converter->toOrder($quote);

            $order->setCreatedAt($quote->getCreatedAt());
            $order->setStore($quote->getStore());

            foreach ($quote->getAllVisibleItems() as $quoteItem)
            {
                $orderItem = $converter->itemToOrderItem($quoteItem);

                $order->addItem($orderItem);
            }

            $address = $converter->addressToOrderAddress($quote->getBillingAddress());

            $order->setBillingAddress($address);

            if ($quote->getShippingAddress() && !$quote->isVirtual())
            {
                $address = $converter->addressToOrderAddress($quote->getShippingAddress());

                $order->setShippingAddress($address);

                $amount      = $quote->getShippingAddress()->getShippingAmount();
                $method      = $quote->getShippingAddress()->getShippingMethod();
                $description = $quote->getShippingAddress()->getShippingDescription();

                $order->setShippingAmount($amount);
                $order->setShippingMethod($method);
                $order->setShippingDescription($description);
            }

            $payment = $converter->paymentToOrderPayment($quote->getPayment());

            $order->setPayment($payment);

            $this->_order = $order;
        }

        return $this->_order->setHistoryEntityName(self::HISTORY_ENTITY_NAME);
    }

    public function setQuote(Mage_Sales_Model_Quote $quote)
    {
        $this->_quote = $quote;

        $this->setQuoteId($quote->getId())
            ->setStoreId($quote->getStoreId())
            ->setCustomerId($quote->getcustomerId())
            ->setSubtotalAmount($quote->getBaseSubtotal())
            ->setTotalAmount($quote->getBaseGrandTotal())
        ;

        if ($quote->getPayment ())
        {
            $this->setPaymentMethod($quote->getPayment()->getMethod());
        }

        if ($quote->getShippingAddress())
        {
            $this->setShippingMethod($quote->getShippingAddress()->getShippingMethod())
                ->setShippingAmount($quote->getShippingAddress()->getBaseShippingAmount());
        }

        if (Mage::helper ('core')->isModuleEnabled('Toluca_PDV') && $quote->getPdvCustomerId() > 0)
        {
            $this->setCustomerId($quote->getPdvCustomerId());
        }

        return $this;
    }

    public function getQuote()
    {
        if (!$this->_quote instanceof Mage_Sales_Model_Quote)
        {
            $this->_quote = Mage::getModel('sales/quote')
                ->setStoreId (Mage_Core_Model_App::DISTRO_STORE_ID)
                ->load($this->getQuoteId());
        }

        return $this->_quote->setHistoryEntityName(self::HISTORY_ENTITY_NAME);
    }

    public function getSubtotal()
    {
        return $this->getSubtotalAmount();
    }

    public function getGrandTotal()
    {
        return $this->getTotalAmount();
    }
}

