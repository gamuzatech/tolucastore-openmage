<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Order Status API
 */
class Gamuza_Basic_Model_Order_Status_Api extends Mage_Api_Model_Resource_Abstract
{
    public function canceled ($incrementId, $protectCode, $comment = null)
    {
        $order = $this->_getOrder ($incrementId, $protectCode);

        if (!strcmp ($order->getStatus (), Gamuza_Basic_Model_Sales_Order::STATUS_CANCELED))
        {
            $this->_fault ('order_has_canceled');
        }

        if (!$order->canCancel ())
        {
            $this->_fault ('order_not_canceled');
        }

        $order->cancel ($comment)->save ();

        Mage::helper ('basic/sales_order_status')->canceled ($order);

        return true;
    }

    public function preparing ($incrementId, $protectCode)
    {
        $order = $this->_getOrder ($incrementId, $protectCode);

        if (!strcmp ($order->getStatus (), Gamuza_Basic_Model_Sales_Order::STATUS_PREPARING))
        {
            $this->_fault ('order_has_prepared');
        }

        if (!$order->canPrepare ())
        {
            $this->_fault ('order_not_prepared');
        }

        Mage::helper ('basic/sales_order_status')->preparing ($order);

        return true;
    }

    public function paid ($incrementId, $protectCode)
    {
        $order = $this->_getOrder ($incrementId, $protectCode);

        if (!strcmp ($order->getStatus (), Gamuza_Basic_Model_Sales_Order::STATUS_PAID))
        {
            $this->_fault ('order_has_paid');
        }

        if (!$order->canInvoice ())
        {
            $this->_fault ('order_not_paid');
        }

        $itemQtys = array ();

        foreach ($order->getAllItems () as $orderItem)
        {
            if ($orderItem->getQtyToInvoice () && !$orderItem->getIsVirtual ())
            {
                $itemQtys [$orderItem->getId ()] = $orderItem->getQtyToInvoice ();
            }
        }

        try
        {
            $invoice = Mage::getModel ('sales/service_order', $order)->prepareInvoice ($itemQtys);

            $invoice->setRequestedCaptureCase (Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
            $invoice->register ();

            $order->setIsInProcess (true);

            Mage::getModel ('core/resource_transaction')
                ->addObject ($invoice)
                ->addObject ($order)
                ->save ()
            ;

            $invoice->sendEmail (true);

            $order->save ();
        }
        catch (Exception $e)
        {
            $this->_fault ('order_not_paid', $e->getMessage ());
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_fault ('order_not_paid', $e->getMessage ());
        }

        Mage::helper ('basic/sales_order_status')->paid ($order);

        return true;
    }

    public function shipped ($incrementId, $protectCode)
    {
        $order = $this->_getOrder ($incrementId, $protectCode);

        if (!strcmp ($order->getStatus (), Gamuza_Basic_Model_Sales_Order::STATUS_SHIPPED))
        {
            $this->_fault ('order_has_shipped');
        }

        if (!$order->canShip ())
        {
            $this->_fault ('order_not_shipped');
        }

        $itemQtys = array ();

        foreach ($order->getAllItems () as $orderItem)
        {
            if ($orderItem->getQtyToShip () && !$orderItem->getIsVirtual ())
            {
                $itemQtys [$orderItem->getId ()] = $orderItem->getQtyToShip ();
            }
        }

        try
        {
            $shipment = Mage::getModel ('sales/service_order', $order)->prepareShipment ($itemQtys);
            $shipment->register ();

            $order->setIsInProcess (true);

            Mage::getModel ('core/resource_transaction')
                ->addObject ($shipment)
                ->addObject ($order)
                ->save ()
            ;

            $shipment->sendEmail (true);

            $order->save ();
        }
        catch (Exception $e)
        {
            $this->_fault ('order_not_shipped', $e->getMessage ());
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_fault ('order_not_shipped', $e->getMessage ());
        }

        Mage::helper ('basic/sales_order_status')->shipped ($order);

        return true;
    }

    public function delivered ($incrementId, $protectCode)
    {
        $order = $this->_getOrder ($incrementId, $protectCode);

        if (!strcmp ($order->getStatus (), Gamuza_Basic_Model_Sales_Order::STATUS_DELIVERED))
        {
            $this->_fault ('order_has_delivered');
        }

        if (!$order->canDeliver ())
        {
            $this->_fault ('order_not_delivered');
        }

        Mage::helper ('basic/sales_order_status')->delivered ($order);

        return true;
    }

    public function refunded ($incrementId, $protectCode)
    {
        $order = $this->_getOrder ($incrementId, $protectCode);

        if (!strcmp ($order->getStatus (), Gamuza_Basic_Model_Sales_Order::STATUS_REFUNDED))
        {
            $this->_fault ('order_has_refunded');
        }

        if (!$order->canCreditmemo ())
        {
            $this->_fault ('order_not_refunded');
        }

        $itemQtys = array ();

        foreach ($order->getAllItems () as $orderItem)
        {
            if ($orderItem->getQtyToRefund () && !$orderItem->getIsVirtual ())
            {
                $itemQtys [$orderItem->getId ()] = $orderItem->getQtyToRefund ();
            }
        }

        try
        {
            $creditmemo = Mage::getModel ('sales/service_order', $order)->prepareCreditmemo ($itemQtys);
            $creditmemo->register ();

            $order->setIsInProcess (true);

            Mage::getModel ('core/resource_transaction')
                ->addObject ($creditmemo)
                ->addObject ($order)
                ->save ()
            ;

            $creditmemo->sendEmail (true);

            $order->save ();
        }
        catch (Exception $e)
        {
            $this->_fault ('order_not_refunded', $e->getMessage ());
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_fault ('order_not_refunded', $e->getMessage ());
        }

        Mage::helper ('basic/sales_order_status')->refunded ($order);

        return true;
    }

    protected function _getOrder ($incrementId, $protectCode)
    {
        if (empty ($incrementId) || empty ($protectCode))
        {
            $this->_fault ('order_not_specified');
        }

        $order = Mage::getModel ('sales/order')->getCollection ()
            ->addFieldToFilter ('increment_id', array ('eq' => $incrementId))
            ->addFieldToFilter ('protect_code', array ('eq' => $protectCode))
            ->getFirstItem ()
        ;

        if (!$order || !$order->getId ())
        {
            $this->_fault ('order_not_exists');
        }

        return $order;
    }
}

