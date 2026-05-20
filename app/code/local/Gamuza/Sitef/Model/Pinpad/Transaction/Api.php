<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Pinpad Transaction API
 */
class Gamuza_Sitef_Model_Pinpad_Transaction_Api extends Mage_Api_Model_Resource_Abstract
{
    /**
     * Attributes map array per entity type
     *
     * @var array
     */
    protected $_attributesMap = array(
        'transaction' => array ()
    );

    protected $_createAttributeList = array(
        'operator_id',
        'merchant_id',
        'terminal_id',
        'payment_id',
        'payment_confirmation',
        'payment_amount',
        'authorization_data',
        'transaction_data',
        'function_id',
        'payment_type',
        'payment_name',
        'payment_description',
        'transaction_datetime',
        'receipt_type',
        'authorizer_id',
        'sitef_nsu',
        'host_nsu',
        'institution_name',
        'establishment_id',
        'authorizer_network_id',
        'payment_sequence',
        'gerpdv_data',
        'nfce_card_brand',
        'nfce_authorization_number',
        'authorization_response_code',
        'sensitive_fields_collect',
        'sensitive_fields_begin',
        'sensitive_fields_end',
    );

    protected $_authorizeAttributeList = array(
        'authorization_data',
        'transaction_data',
        'payment_name',
        'payment_description',
        'sitef_nsu',
        'host_nsu',
        'institution_name',
        'gerpdv_data',
    );

    protected $_cancelAttributeList = array(
        'operator_id',
        'merchant_id',
        'terminal_id',
        'payment_id',
        'payment_confirmation',
        'payment_amount',
        'authorization_data',
        'payment_sequence',
    );

    protected $_cardAttributeList = array(
        'card_brand',
        'authorization_code',
        'card_bin',
        'card_expiration_date',
        'card_holder_name',
        'card_last_digits',
        'card_number',
        'card_holder_birth_date',
        'card_name',
        'card_read_type',
        'card_read_status',
        'transaction_identifier_nit',
        'debit_bill_payment_supported',
        'credit_bill_payment_supported',
        'paper_signature_required',
    );

    public function items ($filters = array ())
    {
        $collection = Mage::getModel ('sitef/pinpad_transaction')->getCollection ();

        /** @var $apiHelper Mage_Api_Helper_Data */
        $apiHelper = Mage::helper ('api');

        $filters = $apiHelper->parseFilters ($filters, $this->_attributesMap ['transaction']);

        try
        {
            foreach ($filters as $field => $value)
            {
                $collection->addFieldToFilter ($field, $value);
            }
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_fault ('filters_invalid', $e->getMessage ());
        }

        $result = array ();

        foreach ($collection as $transaction)
        {
            $data = array (
                'entity_id' => intval ($transaction->getId ()),
            );

            $result [] = $data;
        }

        return $result;
    }

    public function create ($orderIncrementId, $orderProtectCode, $data)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        if (empty ($data))
        {
            $this->_fault ('data_not_specified');
        }

        $order = $this->_initOrder ($orderIncrementId, $orderProtectCode);

        $transaction = $this->_initTransaction ($order, true);

        if (!strcmp ($transaction->getStatus (), Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_AUTHORIZED))
        {
            $this->_fault ('transaction_already_authorized');
        }

        if (!strcmp ($transaction->getStatus (), Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_CANCELED))
        {
            $this->_fault ('transaction_already_canceled');
        }

        if (array_key_exists ('payment_id', $data))
        {
            $paymentId = $data ['payment_id'];

            if (in_array ($paymentId, array(
                Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_DEBIT,
                Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_CREDIT,
            )))
            {
                $this->_createAttributeList = array_merge ($this->_createAttributeList, $this->_cardAttributeList);
            }
        }

        foreach ($this->_createAttributeList as $attribute)
        {
            if (array_key_exists ($attribute, $data))
            {
                $transaction->setData ($attribute, $data [$attribute]);
            }
            else
            {
                $customMessage = Mage::helper ('sitef')->__('Requested data not specified.') . PHP_EOL
                    . PHP_EOL . Mage::helper ('sitef')->__('Attribute name: %s', $attribute);

                $this->_fault ('data_not_specified', $customMessage);
            }
        }

        $transaction->setStatus (Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_UPDATED)
            ->setUpdatedAt (date ('c'))
            ->save ()
        ;

        return $this->_getTransaction ($transaction);
    }

    public function authorize ($orderIncrementId, $orderProtectCode, $data)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        if (empty ($data))
        {
            $this->_fault ('data_not_specified');
        }

        $order = $this->_initOrder ($orderIncrementId, $orderProtectCode);

        $transaction = $this->_initTransaction ($order, true);

        if (!strcmp ($transaction->getStatus (), Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_AUTHORIZED))
        {
            $this->_fault ('transaction_already_authorized');
        }

        if (!strcmp ($transaction->getStatus (), Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_CANCELED))
        {
            $this->_fault ('transaction_already_canceled');
        }

        if (array_key_exists ('payment_id', $data))
        {
            $paymentId = $data ['payment_id'];

            if (in_array ($paymentId, array(
                Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_DEBIT,
                Gamuza_Sitef_Helper_Data::API_PAYMENT_METHOD_CREDIT,
            )))
            {
                $this->_authorizeAttributeList = array_merge ($this->_authorizeAttributeList, array(
                    'authorization_code',
                    'card_number',
                    'card_name',
                ));
            }
        }

        /*
        foreach ($this->_authorizeAttributeList as $attribute)
        {
            if (array_key_exists ($attribute, $data))
            {
                $transaction->setData ($attribute, $data [$attribute]);
            }
            else
            {
                $customMessage = Mage::helper ('sitef')->__('Requested data not specified.') . PHP_EOL
                    . PHP_EOL . Mage::helper ('sitef')->__('Attribute name: %s', $attribute);

                $this->_fault ('data_not_specified', $customMessage);
            }
        }
        */

        $transaction->setStatus (Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_AUTHORIZED)
            ->setAuthorizedAt (date ('c'))
            ->save ()
        ;

        $payment = $order->getPayment ();

        $paymentTransId = $payment->getData (Gamuza_Sitef_Helper_Data::PAYMENT_ATTRIBUTE_SITEF_TRANS_ID);

        $paymentTrans = $payment->getTransaction ($paymentTransId);

        if ($paymentTrans && $paymentTrans->getId ())
        {
            $paymentTransAdditionalInformation = array ();

            foreach ($this->_authorizeAttributeList as $attribute)
            {
                if (array_key_exists ($attribute, $data))
                {
                    $paymentTransAdditionalInformation [$attribute] = $data [$attribute];
                }
                else
                {
                    $customMessage = Mage::helper ('sitef')->__('Requested data not specified.') . PHP_EOL
                        . PHP_EOL . Mage::helper ('sitef')->__('Attribute name: %s', $attribute);

                    $this->_fault ('data_not_specified', $customMessage);
                }
            }

            $paymentTrans->setIsClosed (1)
                ->setAdditionalInformation (
                    Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS,
                    $paymentTransAdditionalInformation
                )
                ->save ()
            ;
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
            $status = Mage::getStoreConfig (Gamuza_Sitef_Helper_Data::XML_PATH_PAYMENT_GAMUZA_SITEF_PINPAD_PAID_STATUS);

            $comment = Mage::helper ('sitef')->__('The payment was approved.');

            $order->setState (Mage_Sales_Model_Order::STATE_NEW, $status, $comment, false)
                ->save ()
            ;

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

            $order->queueOrderUpdateEmail (true, $comment, true)
                ->addStatusToHistory ($status, $comment, true)
                ->save ()
            ;
        }
        catch (Exception $e)
        {
            Mage::log ($e->getMessage (), null, Gamuza_Sitef_Helper_Data::LOG, true);
        }

        return $this->_getTransaction ($transaction);
    }

    public function cancel ($orderIncrementId, $orderProtectCode, $data)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        if (empty ($data))
        {
            $this->_fault ('data_not_specified');
        }

        $order = $this->_initOrder ($orderIncrementId, $orderProtectCode);

        $transaction = $this->_initTransaction ($order, true);

        /*
        if (!strcmp ($transaction->getStatus (), Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_AUTHORIZED))
        {
            $this->_fault ('transaction_already_authorized');
        }
        */

        if (!strcmp ($transaction->getStatus (), Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_CANCELED))
        {
            $this->_fault ('transaction_already_canceled');
        }

        foreach ($this->_cancelAttributeList as $attribute)
        {
            if (array_key_exists ($attribute, $data))
            {
                $transaction->setData ($attribute, $data [$attribute]);
            }
            else
            {
                $customMessage = Mage::helper ('sitef')->__('Requested data not specified.') . PHP_EOL
                    . PHP_EOL . Mage::helper ('sitef')->__('Attribute name: %s', $attribute);

                $this->_fault ('data_not_specified', $customMessage);
            }
        }

        $transaction->setStatus (Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_CANCELED)
            ->setCanceledAt (date ('c'))
            ->save ()
        ;

        $payment = $order->getPayment ();

        $paymentTransId = $payment->getData (Gamuza_Sitef_Helper_Data::PAYMENT_ATTRIBUTE_SITEF_TRANS_ID);

        $paymentTrans = $payment->getTransaction ($paymentTransId);

        if ($paymentTrans && $paymentTrans->getId ())
        {
            $paymentTransAdditionalInformation = array ();

            foreach ($this->_cancelAttributeList as $attribute)
            {
                if (array_key_exists ($attribute, $data))
                {
                    $paymentTransAdditionalInformation [$attribute] = $data [$attribute];
                }
                else
                {
                    $customMessage = Mage::helper ('sitef')->__('Requested data not specified.') . PHP_EOL
                        . PHP_EOL . Mage::helper ('sitef')->__('Attribute name: %s', $attribute);

                    $this->_fault ('data_not_specified', $customMessage);
                }
            }

            $paymentTrans->setIsClosed (1)
                ->setAdditionalInformation (
                    Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS,
                    $paymentTransAdditionalInformation
                )
                ->save ()
            ;
        }

        try
        {
            $comment = Mage::helper ('sitef')->__('The payment was denied.');

            $order->setState (Mage_Sales_Model_Order::STATE_NEW, Mage_Sales_Model_Order::STATE_CANCELED, $comment, true)
                ->save ()
            ;

            $order->queueOrderUpdateEmail (true, $comment, true);

            $order->cancel ();

            $order->queueOrderUpdateEmail (true, $comment, true)
                ->addStatusToHistory (false, $comment, true)
                ->save ()
            ;
        }
        catch (Exception $e)
        {
            Mage::log ($e->getMessage (), null, Gamuza_Sitef_Helper_Data::LOG, true);
        }

        return $this->_getTransaction ($transaction);
    }

    /**
     * Initialize order model
     *
     * @param mixed $orderIncrementId
     * @param mixed $orderProtectCode
     * @return Mage_Sales_Model_Order
     */
    protected function _initOrder ($orderIncrementId, $orderProtectCode)
    {
        $order = Mage::getModel ('basic/sales_order')->getCollection ()
            ->addFieldToFilter ('increment_id', array ('eq' => $orderIncrementId))
            ->addFieldToFilter ('protect_code', array ('eq' => $orderProtectCode))
            ->getFirstItem ()
        ;

        if (!$order || !$order->getId ())
        {
            $this->_fault ('order_not_exists');
        }

        return $order;
    }

    protected function _initTransaction ($order, $fault = true)
    {
        $transaction = Mage::getModel ('sitef/pinpad_transaction')->load ($order->getId (), 'order_id');

        if ($fault && (!$transaction || !$transaction->getId ()))
        {
            $this->_fault ('transaction_not_exists');
        }

        return $transaction;
    }

    protected function _getTransaction ($transaction)
    {
        $result = array ();

        $items = $this->items (array(
            'main_table.entity_id' => $transaction->getId (),
        ));

        if (count ($items) > 0)
        {
            $result = reset ($items);
        }

        return $result;
    }
}

