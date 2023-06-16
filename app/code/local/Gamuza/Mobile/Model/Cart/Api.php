<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Mobile_Model_Cart_Api extends Mage_Checkout_Model_Api_Resource
{
    use Gamuza_Mobile_Trait_Api_Resource;

    protected $_quoteAmountAttributes = array(
        'items_count', 'items_qty',
        'grand_total', 'base_grand_total',
        'subtotal', 'base_subtotal',
        'subtotal_with_discount', 'base_subtotal_with_discount',
    );

    protected $_intAttributes = array(
        /* info */
        'items_count', 'customer_gender',
        /* address */
        'region_id'
    );

    protected $_floatAttributes = array(
        /* info */
        'items_qty', 'grand_total', 'base_grand_total', 'subtotal', 'base_subtotal', 'subtotal_with_discount', 'base_subtotal_with_discount',
        /* address */
        'weight', 'shipping_amount', 'base_shipping_amount', 'discount_amount', 'base_discount_amount', 'shipping_discount_amount', 'base_shipping_discount_amount',
        /* item */
        'price', 'base_price', 'custom_price', 'discount_percent', 'row_total', 'base_row_total', 'row_total_with_discount', 'row_weight'
    );

    protected $_boolAttributes = array(
        'is_app', 'is_bot', 'is_zap', 'is_pdv',
        'is_openpix', 'is_pagcripto', 'is_picpay',
    );

    protected $_orderAttributes = array(
        'increment_id', 'protect_code', 'coupon_code',
        'shipping_method', 'shipping_description',
        'base_shipping_discount_amount',
        'base_discount_amount', 'base_shipping_amount', 'base_tax_amount',
        'base_subtotal', 'base_grand_total',
        'total_item_count', 'total_qty_ordered',
        'base_currency_code',
        'customer_firstname', 'customer_lastname', 'customer_taxvat',
        'weight', 'bot_type',
        'is_app', 'is_bot', 'is_zap', 'is_pdv',
        'is_openpix', 'is_pagcripto', 'is_picpay',
    );

    /**
     * Retrieve amount information about quote
     *
     * @param  $quoteId
     * @param  $store
     * @return array
     */
    public function amount($code = null, $store = null)
    {
        if (empty ($code))
        {
            $this->_fault ('customer_code_not_specified');
        }

        $quote = $this->_getCustomerQuote($code, $store);

        $result = $this->_getAttributes($quote, 'quote', $this->_quoteAmountAttributes);

        foreach ($this->_intAttributes as $code)
        {
            if (array_key_exists ($code, $result))
            {
                $result [$code] = intval ($result [$code]);
            }
        }

        foreach ($this->_floatAttributes as $code)
        {
            if (array_key_exists ($code, $result))
            {
                $result [$code] = floatval ($result [$code]);
            }
        }

        return $result;
    }

    /**
     * @param  $quoteId
     * @param  $store
     * @return void
     */
    public function totals($code = null, $store = null)
    {
        if (empty ($code))
        {
            $this->_fault ('customer_code_not_specified');
        }

        $quote = $this->_getCustomerQuote($code, $store);

        $totals = $quote->getTotals();

        $totalsResult = array();

        foreach ($totals as $total)
        {
            $totalsResult[] = array(
                "title" => $total->getTitle(),
                "amount" => floatval($total->getValue()),
                "quote_id" => intval($quote->getId()),
            );
        }

        return $totalsResult;
    }

    /**
     * Create an order from the shopping cart (quote)
     *
     * @param  $quoteId
     * @param  $store
     * @param  $agreements array
     * @return string
     */
    public function createOrder($code = null, $agreements = null, $store = null)
    {
        $order = $this->_createOrder ($code, $agreements, $store);

        $result = array(
            'order' => $this->_getAttributes ($order, 'order', $this->_orderAttributes),
            'pagcripto'    => null,
            'picpay'       => null,
            'openpix'      => null,
            'pagseguropro' => null,
        );

        $result ['order']['payment_method'] = $order->getPayment ()->getMethod ();

        foreach ($this->_boolAttributes as $code)
        {
            if (array_key_exists ($code, $result ['order']))
            {
                $result ['order'][$code] = boolval ($result ['order'][$code]);
            }
        }

        $payment = $order->getPayment ();

        if (Mage::helper ('core')->isModuleEnabled ('Gamuza_PagCripto')
            && !strcmp ($payment->getMethod (), Gamuza_PagCripto_Model_Payment_Method_Payment::CODE))
        {
            $transaction = Mage::getModel ('pagcripto/transaction')->load ($order->getIncrementId(), 'order_increment_id');

            if ($transaction && $transaction->getId ())
            {
                $result ['pagcripto'] = array (
                    'status'   => $transaction->getStatus (),
                    'currency' => $transaction->getCurrency (),
                    'address'  => $transaction->getAddress (),
                    'amount'   => $transaction->getAmount (),
                );
            }
        }

        if (Mage::helper ('core')->isModuleEnabled ('Gamuza_PicPay')
            && !strcmp ($payment->getMethod (), Gamuza_PicPay_Model_Payment_Method_Payment::CODE))
        {
            $transaction = Mage::getModel ('picpay/transaction')->load ($order->getIncrementId(), 'order_increment_id');

            if ($transaction && $transaction->getId ())
            {
                $result ['picpay'] = array (
                    'status' => $transaction->getStatus (),
                    'url'    => $transaction->getPaymentUrl (),
                    'qrcode_content' => $transaction->getQrcodeContent (),
                    'qrcode_base64'  => $transaction->getQrcodeBase64 (),
                );
            }
        }

        if (Mage::helper ('core')->isModuleEnabled ('Gamuza_OpenPix')
            && !strcmp ($payment->getMethod (), Gamuza_OpenPix_Model_Payment_Method_Payment::CODE))
        {
            $transaction = Mage::getModel ('openpix/transaction')->load ($order->getIncrementId(), 'order_increment_id');

            if ($transaction && $transaction->getId ())
            {
                $result ['openpix'] = array (
                    'status' => $transaction->getStatus (),
                    'url'    => $transaction->getPaymentLinkUrl (),
                    'qrcode_image_url' => $transaction->getQrcodeImageUrl (),
                );
            }
        }

        if (Mage::helper ('core')->isModuleEnabled ('RicardoMartins_PagSeguroPro')
            && !strcmp ($payment->getMethod (), RicardoMartins_PagSeguroPro_Model_Payment_Boleto::CODE))
        {
            $result ['pagseguropro'] = array (
                'transaction_id' => $payment->getAdditionalInformation ('transaction_id'),
                'billet_url'     => $payment->getAdditionalInformation ('boletoUrl'),
            );
        }

        return $result;
    }

    /**
     * Create an order from the shopping cart (quote)
     *
     * @param  $quoteId
     * @param  $store
     * @param  $agreements array
     * @return string
     */
    protected function _createOrder($code = null, $agreements = null, $store = null)
    {
        if (empty ($code))
        {
            $this->_fault ('customer_code_not_specified');
        }

        $requiredAgreements = Mage::helper('checkout')->getRequiredAgreementIds();

        if (!empty($requiredAgreements))
        {
            if (empty ($agreements) || !is_array($agreements))
            {
                $this->_fault ('required_agreements_are_not_specified');
            }

            $diff = array_diff($agreements, $requiredAgreements);

            if (!empty($diff))
            {
                $this->_fault('required_agreements_are_not_all');
            }
        }

        $quote = $this->_getCustomerQuote($code, $store);

        if ($quote->getIsMultiShipping())
        {
            $this->_fault('invalid_checkout_type');
        }

        if ($quote->getCheckoutMethod() == Mage_Checkout_Model_Api_Resource_Customer::MODE_GUEST
                && !Mage::helper('checkout')->isAllowedGuestCheckout($quote, $quote->getStoreId())
        )
        {
            $this->_fault('guest_checkout_is_not_enabled');
        }

        /** @var $customerResource Mage_Checkout_Model_Api_Resource_Customer */
        $customerResource = Mage::getModel("checkout/api_resource_customer");

        $isNewCustomer = $customerResource->prepareCustomerForQuote($quote);

        /*
         * Validate: is_in_stock
         */
        foreach ($quote->getAllItems () as $item)
        {
            $stockItem = Mage::getModel ('catalogInventory/stock_item')->loadByProduct ($item->getProductId ());

            if (!$stockItem || !$stockItem->getId () || !$stockItem->getIsInStock ())
            {
                $this->_fault ('create_order_fault', Mage::helper ('mobile')->__('This product is currently out of stock: %s', $item->getProduct ()->getName ()));
            }
        }

        try
        {
            Mage::getSingleton('api/session')->setData('PsPayment', serialize($quote->getPayment()->getAdditionalInformation())); // pagseguro_cc

            $quote->collectTotals();

            /** @var $service Mage_Sales_Model_Service_Quote */
            $service = Mage::getModel('sales/service_quote', $quote);
            $service->submitAll();

            if ($isNewCustomer)
            {
                try
                {
                    $customerResource->involveNewCustomer($quote);
                }
                catch (Exception $e)
                {
                    Mage::logException($e);
                }
            }

            $order = $service->getOrder();

            if ($order)
            {
                Mage::dispatchEvent('checkout_type_onepage_save_order_after',
                    array('order' => $order, 'quote' => $quote)
                );

                try
                {
                    $order->queueNewOrderEmail();
                }
                catch (Exception $e)
                {
                    Mage::logException($e);
                }
            }

            Mage::dispatchEvent('checkout_submit_all_after',
                array('order' => $order, 'quote' => $quote)
            );

            $quote->delete (); // discard
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_fault('create_order_fault', $e->getMessage());
        }

        return $order;
    }

    /**
     * @param  $store
     * @return bool
     */
    public function clear ($code = null, $store = null)
    {
        if (empty ($code))
        {
            $this->_fault ('customer_code_not_specified');
        }

        $quote = $this->_getCustomerQuote ($code, $store);

        $quote->delete ();

        return true;
    }

    public function draft ($code = null, $store = null)
    {
        if (empty ($code))
        {
            $this->_fault ('customer_code_not_specified');
        }

        $quote = $this->_getCustomerQuote ($code, $store);

        $result = Mage::app ()
            ->getLayout ()
            ->createBlock ('mobile/adminhtml_order_draft')
            ->setArea (Mage_Core_Model_App_Area::AREA_ADMINHTML)
            ->setOrder ($quote)
            ->setTemplate ('gamuza/mobile/order/draft.phtml')
            ->toHtml ();

        return $result;
    }
}

