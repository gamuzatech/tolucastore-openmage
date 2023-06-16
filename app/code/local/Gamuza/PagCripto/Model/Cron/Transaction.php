<?php
/**
 * @package     Gamuza_PagCripto
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PagCripto_Model_Cron_Transaction extends Gamuza_PagCripto_Model_Cron_Abstract
{
    public function run ()
    {
        $collection = Mage::getModel ('sales/order')->getCollection ()
            ->addFieldToFilter (Gamuza_PagCripto_Helper_Data::ORDER_ATTRIBUTE_IS_PAGCRIPTO, array ('eq' => true))
            ->addFieldToFilter ('main_table.state', array ('eq' => Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW))
        ;

        foreach ($collection as $order)
        {
            $payment = $order->getPayment ();

            $paymentRequest = $payment->getData (Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_PAYMENT_REQUEST);

            $apiStatusUrl = str_replace ('{id}', $paymentRequest, Gamuza_PagCripto_Helper_Data::API_PAYMENT_CHECK_URL);

            try
            {
                $resultStatus = Mage::helper ('pagcripto')->api ($apiStatusUrl, null, null, $order->getStoreId ());
            }
            catch (Exception $e)
            {
                $this->message ($e->getMessage ());

                /* fake */
                $resultStatus ['payment-details']['status'] = Gamuza_PagCripto_Helper_Data::API_PAYMENT_STATUS_ERROR;
                $resultStatus ['payment-details']['message'] = $e->getMessage ();
            }

            $paymentDetailsStatus = $resultStatus ['payment-details']['status'];
            $paymentDetailsMessage = $resultStatus ['payment-details']['message'];
            $paymentDetailsReceivedAmount = $resultStatus ['payment-details']['received_amount'];
            $paymentDetailsTxID = $resultStatus ['payment-details']['txid'];
            $paymentDetailsConfirmations = $resultStatus ['payment-details']['confirmations'];

            $payment->setData (Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_STATUS, $paymentDetailsStatus)
                ->setData (Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_CONFIRMATIONS, $paymentDetailsConfirmations)
                ->setData (Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_RECEIVED_AMOUNT, $paymentDetailsReceivedAmount)
                ->save ()
            ;

            $transaction = Mage::getModel('pagcripto/transaction')->load ($order->getId (), 'order_id')
                ->setUpdatedAt (date ('c'))
                ->setStatus ($paymentDetailsStatus)
                ->setMessage ($paymentDetailsMessage)
                ->setReceivedAmount ($paymentDetailsReceivedAmount)
                ->setTxid ($paymentDetailsTxID)
                ->setConfirmations ($paymentDetailsConfirmations)
                ->save ()
            ;

            switch ($paymentDetailsStatus)
            {
                case Gamuza_PagCripto_Helper_Data::API_PAYMENT_STATUS_WAITING_FOR_PAYMENT:
                case Gamuza_PagCripto_Helper_Data::API_PAYMENT_STATUS_WAITING_FOR_CONFIRMATION:
                {
                    break; // nothing
                }
                case Gamuza_PagCripto_Helper_Data::API_PAYMENT_STATUS_ERROR:
                {
                    $comment = Mage::helper ('pagcripto')->__('The payment was expired.');

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
                case Gamuza_PagCripto_Helper_Data::API_PAYMENT_STATUS_PAID:
                {
                    $payment->setData (Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_EXT_PAYMENT_ID, $paymentDetailsTxID)
                        ->setTransactionId ($paymentDetailsTxID)
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

                        $comment = Mage::helper ('pagcripto')->__('The payment was approved.');

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

