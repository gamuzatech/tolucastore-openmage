<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Shopping cart api
 */
class Gamuza_Mobile_Model_Cart_Payment_Api extends Mage_Checkout_Model_Cart_Payment_Api
{
    use Gamuza_Mobile_Trait_Api_Resource;

    /**
     * Retrieve available payment methods for a quote
     *
     * @param int $quoteId
     * @param int $store
     * @return array
     */
    public function _getPaymentMethodsList($code = null, $store = null)
    {
        if (empty ($code))
        {
            $this->_fault ('customer_code_not_specified');
        }

        $quote = $this->_getCustomerQuote($code, $store);

        $total = $quote->getBaseSubtotal();

        $storeId = Mage::getStoreConfig (Gamuza_Mobile_Helper_Data::XML_PATH_API_MOBILE_STORE_VIEW, $store);

        $methodsResult = array();
        $methods = Mage::helper('payment')->getStoreMethods($storeId, $quote);

        foreach ($methods as $method)
        {
            /** @var $method Mage_Payment_Model_Method_Abstract */
            if ($this->_canUsePaymentMethod($method, $quote))
            {
                $isRecurring = $quote->hasRecurringItems() && $method->canManageRecurringProfiles();

                if ($total != 0 || $method->getCode() == 'free' || $isRecurring)
                {
                    $methodsResult[] = array(
                        'code' => $method->getCode(),
                        'title' => $method->getTitle(),
                        'cc_types' => $this->_getPaymentMethodAvailableCcTypes($method),
                        'base_grand_total' => floatval ($quote->getBaseGrandTotal ()),
                        'installment_limit' => intval ($method->getConfigData ('installment_limit')),
                        /* creditcard */
                        'installments' => $this->_getCcAvailableInstallments ($quote, $method),
                        /* pagseguro */
                        'session_id' => $this->_getRemoteSessionId ($quote, $method),
                        /* checkmo */
                        'mailing_address' => $method->getConfigData('mailing_address'),
                        /* banktransfer */
                        'instructions' => $method->getConfigData('instructions')
                    );
                }
            }
        }

        return $methodsResult;
    }

    /**
     * @param  $quoteId
     * @param  $paymentData
     * @param  $store
     * @return bool
     */
    public function _setPaymentMethod($code = null, $paymentData = null, $store = null)
    {
        if (empty ($code))
        {
            $this->_fault ('customer_code_not_specified');
        }

        $quote = $this->_getCustomerQuote($code, $store);

        $paymentData = $this->_preparePaymentData($paymentData);

        if (empty($paymentData))
        {
            $this->_fault("payment_method_empty");
        }

        if ($quote->isVirtual())
        {
            // check if billing address is set
            if (is_null($quote->getBillingAddress()->getId()))
            {
                $this->_fault('billing_address_is_not_set');
            }

            $quote->getBillingAddress()->setPaymentMethod(
                isset($paymentData['method']) ? $paymentData['method'] : null
            );
        }
        else
        {
            // check if shipping address is set
            if (is_null($quote->getShippingAddress()->getId()))
            {
                $this->_fault('shipping_address_is_not_set');
            }

            $quote->getShippingAddress()->setPaymentMethod(
                isset($paymentData['method']) ? $paymentData['method'] : null
            );
        }

        if (!$quote->isVirtual() && $quote->getShippingAddress())
        {
            $quote->getShippingAddress()->setCollectShippingRates(true);
        }

        $total = $quote->getBaseSubtotal();

        $storeId = Mage::getStoreConfig (Gamuza_Mobile_Helper_Data::XML_PATH_API_MOBILE_STORE_VIEW, $store);

        $methods = Mage::helper('payment')->getStoreMethods($storeId, $quote);

        foreach ($methods as $method)
        {
            if ($method->getCode() == $paymentData['method'])
            {
                /** @var $method Mage_Payment_Model_Method_Abstract */
                if (!($this->_canUsePaymentMethod($method, $quote)
                    && ($total != 0
                        || $method->getCode() == 'free'
                        || ($quote->hasRecurringItems() && $method->canManageRecurringProfiles())))
                )
                {
                    $this->_fault("method_not_allowed");
                }
            }
        }

        try
        {
            $payment = $quote->getPayment();
            $payment->importData($paymentData);

            $quote->setTotalsCollectedFlag(false)
                ->collectTotals()
                ->save();
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_fault('payment_method_is_not_set', $e->getMessage());
        }

        return true;
    }
}

