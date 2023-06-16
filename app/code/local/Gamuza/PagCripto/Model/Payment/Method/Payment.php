<?php
/**
 * @package     Gamuza_PagCripto
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PagCripto_Model_Payment_Method_Payment extends Mage_Payment_Model_Method_Cc
{
    const CODE = 'gamuza_pagcripto_payment';

    protected $_code = self::CODE;

    protected $_canOrder = true;

    protected $_formBlockType = 'pagcripto/payment_form_payment';
    protected $_infoBlockType = 'pagcripto/payment_info_payment';

    const API_PAYMENT_STATUS_WAITING_FOR_PAYMENT = Gamuza_PagCripto_Helper_Data::API_PAYMENT_STATUS_WAITING_FOR_PAYMENT;

    /**
     * Validate payment method information object
     *
     * @param   Mage_Payment_Model_Info $info
     * @return  Mage_Payment_Model_Abstract
     */
    public function validate ()
    {
        /*
        * calling parent validate function
        */
        return Mage_Payment_Model_Method_Abstract::validate ();
    }

    /**
     * Order payment abstract method
     *
     * @param Varien_Object $payment
     * @param float $amount
     *
     * @return Mage_Payment_Model_Abstract
     */
    public function order (Varien_Object $payment, $amount)
    {
        parent::order ($payment, $amount);

        $order = $payment->getOrder ();

        /**
         * Transaction
         */
        $storeName = Mage::getStoreConfig (Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME);

        $callbackUrl = Mage::getUrl ('picpay/payment/callback', array(
            '_secure' => true,
            '_nosid'  => true,
            '_store' => $order->getStoreId (),
        ));

        $post = array(
            'currency' => $payment->getCcType (),
            'amount' => $order->getBaseGrandTotal (),
            'description' => sprintf ('%s %s (%s)',
                Mage::helper ('pagcripto')->__('Order'),
                $order->getIncrementId (), $storeName,
            ),
            'callback' => $callbackUrl,
        );

        try
        {
            $result = Mage::helper ('pagcripto')->api (Gamuza_PagCripto_Helper_Data::API_PAYMENT_CREATE_URL, $post, null, $order->getStoreId ());

            if (empty ($result) || !is_array ($result)
                || !array_key_exists ('payment-details', $result)
                || !array_key_exists ('customer-details', $result))
            {
                throw new Exception (Mage::helper ('pagcripto')->__('Receveid an empty PagCripto transaction.'));
            }

            $paymentDetailsAddress = $result ['payment-details']['address'];
            $paymentDetailsAmount = $result ['payment-details']['amount'];
            $paymentDetailsPaymentRequest = $result ['payment-details']['payment_request'];

            $customerDetailsDescription = $result ['customer-details']['description'];

            $payment->setData (Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_CURRENCY, $payment->getCcType ())
                ->setData (Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_ADDRESS, $paymentDetailsAddress)
                ->setData (Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_AMOUNT, $paymentDetailsAmount)
                ->setData (Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_STATUS, self::API_PAYMENT_STATUS_WAITING_FOR_PAYMENT)
                ->setData (Gamuza_PagCripto_Helper_Data::PAYMENT_ATTRIBUTE_PAGCRIPTO_PAYMENT_REQUEST, $paymentDetailsPaymentRequest)
                ->save ()
            ;

            $transaction = Mage::getModel ('pagcripto/transaction')
                ->setStatus (self::API_PAYMENT_STATUS_WAITING_FOR_PAYMENT)
                ->setMessage (new Zend_Db_Expr ('NULL'))
                ->setCreatedAt (date ('c'))
                /* Params */
                ->setStoreId ($order->getStoreId ())
                ->setCustomerId ($order->getCustomerId ())
                ->setOrderId ($order->getId ())
                ->setOrderIncrementId ($order->getIncrementId ())
                ->setCurrency ($payment->getCcType ())
                /* Result */
                ->setAddress ($paymentDetailsAddress)
                ->setAmount ($paymentDetailsAmount)
                ->setPaymentRequest ($paymentDetailsPaymentRequest)
                ->setDescription ($customerDetailsDescription)
                ->save ()
            ;
        }
        catch (Exception $e)
        {
            Mage::log ($e->getMessage (), null, Gamuza_PagCripto_Helper_Data::LOG, true);

            throw new Mage_Core_Exception (
                Mage::helper ('pagcripto')->__('There was an error in the PAGCRIPTO transaction. Please try again!')
            );
        }

        $payment->setSkipOrderProcessing (false);
        $payment->setIsTransactionPending (true);

        return $this;
    }

    /**
     * Get instructions text from config
     *
     * @return string
     */
    public function getInstructions()
    {
        return trim($this->getConfigData('instructions'));
    }
}

