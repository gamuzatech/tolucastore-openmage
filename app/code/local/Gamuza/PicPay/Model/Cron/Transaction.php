<?php
/**
 * @package     Gamuza_PicPay
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PicPay_Model_Cron_Transaction extends Gamuza_PicPay_Model_Cron_Abstract
{
    public function run ()
    {
        $collection = Mage::getModel ('sales/order')->getCollection ()
            ->addFieldToFilter (Gamuza_PicPay_Helper_Data::ORDER_ATTRIBUTE_IS_PICPAY, array ('eq' => true))
            ->addFieldToFilter ('main_table.state', array ('eq' => Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW))
        ;

        foreach ($collection as $order)
        {
            $referenceId = Mage::helper ('picpay')->getOrderReferenceId ($order);

            $apiStatusUrl = str_replace ('{referenceId}', $referenceId, Gamuza_PicPay_Helper_Data::API_PAYMENTS_STATUS_URL);

            try
            {
                $resultStatus = Mage::helper ('picpay')->api ($apiStatusUrl, null, null, $order->getStoreId ());
            }
            catch (Exception $e)
            {
                $this->message ($e->getMessage ());

                /* fake */
                $resultStatus = new stdClass;
                $resultStatus->status = Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_ERROR;
            }

            $payment = $order->getPayment ();

            $payment->setData (Gamuza_PicPay_Helper_Data::PAYMENT_ATTRIBUTE_PICPAY_STATUS, $resultStatus->status)
                ->save ()
            ;

            $transaction = Mage::getModel('picpay/transaction')->load ($order->getId (), 'order_id')
                ->setStatus ($resultStatus->status)
                ->save ()
            ;

            switch ($resultStatus->status)
            {
                case Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_CREATED:
                case Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_ANALYSIS:
                {
                    break; // nothing
                }
                case Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_EXPIRED:
                case Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_REFUNDED:
                case Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_CHARGEDBACK:
                case Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_ERROR:
                {
                    $comment = Mage::helper ('picpay')->__('The payment was expired.');

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
                case Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_PAID:
                case Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_COMPLETED:
                {
                    $payment->setData (Gamuza_PicPay_Helper_Data::PAYMENT_ATTRIBUTE_EXT_PAYMENT_ID, $resultStatus->authorizationId)
                        ->setTransactionId ($resultStatus->authorizationId)
                        ->setIsTransactionClosed (0)
                        ->save ()
                    ;

                    $transaction->setAuthorizationId ($resultStatus->authorizationId)
                        ->setUpdatedAt (date ('c'))
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

                        $comment = Mage::helper ('picpay')->__('The payment was approved.');

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

