<?php
/**
 * @package     Gamuza_OpenPix
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_OpenPix_Model_Cron_Transaction extends Gamuza_OpenPix_Model_Cron_Abstract
{
    public function run ()
    {
        $collection = Mage::getModel ('sales/order')->getCollection ()
            ->addFieldToFilter (Gamuza_OpenPix_Helper_Data::ORDER_ATTRIBUTE_IS_OPENPIX, array ('eq' => true))
            ->addFieldToFilter ('main_table.state', array ('eq' => Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW))
        ;

        foreach ($collection as $order)
        {
            $payment = $order->getPayment ();

            $transactionId = $payment->getData (Gamuza_OpenPix_Helper_Data::PAYMENT_ATTRIBUTE_OPENPIX_TRANSACTION_ID);

            if (empty ($transactionId)) $transactionId = $payment->getData (Gamuza_OpenPix_Helper_Data::PAYMENT_ATTRIBUTE_OPENPIX_CORRELATION_ID);

            $apiStatusUrl = str_replace ('{id}', $transactionId, Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_URL);

            try
            {
                $resultStatus = Mage::helper ('openpix')->api ($apiStatusUrl, null, null, $order->getStoreId ());
            }
            catch (Exception $e)
            {
                $this->message ($e->getMessage ());

                /* fake */
                $resultStatus = new stdClass;
                $resultStatus->charge = new stdClass;
                $resultStatus->charge->status = Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_ERROR;
            }

            $payment->setData (Gamuza_OpenPix_Helper_Data::PAYMENT_ATTRIBUTE_OPENPIX_STATUS, $resultStatus->charge->status)
                ->save ()
            ;

            $transaction = Mage::getModel('openpix/transaction')->load ($order->getId (), 'order_id')
                ->setUpdatedAt (date ('c'))
                ->setStatus ($resultStatus->charge->status)
                ->save ()
            ;

            switch ($resultStatus->charge->status)
            {
                case Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_ACTIVE:
                {
                    break; // nothing
                }
                case Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_EXPIRED:
                case Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_ERROR:
                {
                    $comment = Mage::helper ('openpix')->__('The payment was expired.');

                    $order->setState (Mage_Sales_Model_Order::STATE_NEW, Mage_Sales_Model_Order::STATE_CANCELED, $comment, true)
                        ->save ()
                    ;

                    $order->queueOrderUpdateEmail (true, $comment, true);

                    $order->cancel ();

                    $order->queueOrderUpdateEmail (true, $comment, true)
                        ->addStatusToHistory (false, $comment, true)
                        ->save ()
                    ;

                    break;
                }
                case Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_COMPLETED:
                {
                    $payment->setData (Gamuza_OpenPix_Helper_Data::PAYMENT_ATTRIBUTE_EXT_PAYMENT_ID, $resultStatus->charge->transactionID)
                        ->setTransactionId ($resultStatus->charge->transactionID)
                        ->setIsTransactionClosed (0)
                        ->save ()
                    ;

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
                        $status = $this->getStoreConfig ('paid_status');

                        $comment = Mage::helper ('openpix')->__('The payment was approved.');

                        $order->setState (Mage_Sales_Model_Order::STATE_NEW, $status, $comment, false)
                            ->save ()
                        ;

                        $invoice = Mage::getModel ('sales/service_order', $order)->prepareInvoice ($itemQtys);

                        $invoice->setRequestedCaptureCase (Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
                        $invoice->register ();

                        $order->setIsInProcess (true);

                        Mage::getModel ('core/resource_transaction')
                            ->addObject ($invoice)
                            ->addObject ($order)
                            ->save ()
                        ;

                        $invoice->sendEmail (true);

                        $order->queueOrderUpdateEmail (true, $comment, true)
                            ->addStatusToHistory ($status, $comment, true)
                            ->save ()
                        ;
                    }
                    catch (Exception $e)
                    {
                        $this->message ($e->getMessage ());
                    }

                    break;
                }
            }
        }
    }
}

