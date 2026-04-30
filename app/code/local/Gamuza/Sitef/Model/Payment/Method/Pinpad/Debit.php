<?php
/**
 * @package     Gamuza_Pinpad
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Sitef_Model_Payment_Method_Pinpad_Debit extends Mage_Payment_Model_Method_Abstract
{
    const CODE = 'sitef_pinpad_debit';

    protected $_code = self::CODE;

    protected $_canOrder = true;

    protected $_formBlockType = 'picpay/payment_form_payment';
    protected $_infoBlockType = 'picpay/payment_info_payment';

    const DEFAULT_CUSTOMER_EMAIL  = 'store@toluca.com.br';
    const DEFAULT_CUSTOMER_TAXVAT = '02788178824';

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
        $callbackUrl = Mage::getUrl ('picpay/payment/callback', array(
            '_secure' => true,
            '_nosid'  => true,
            '_store' => $order->getStoreId (),
        ));

        $customerTaxvat = preg_replace ('[\D]', '', $order->getCustomerTaxvat ());

        $customerEmail = $order->getCustomerEmail ();

        $customerFirstname = $order->getBillingAddress ()->getFirstname ();
        $customerLastname  = $order->getBillingAddress ()->getLastname ();

        $customerPhone = preg_replace ('[\D]', '', $order->getBillingAddress ()->getCellphone ());

        $post = array(
            'referenceId' => Mage::helper ('picpay')->getOrderReferenceId ($order),
            'callbackUrl' => $callbackUrl,
            'returnUrl'   => null,
            'value'       => floatval ($order->getBaseGrandTotal ()),
            'expiresAt'   => null,
            'purchaseMode' => 'online',
            'buyer' => array(
                'firstName' => $customerFirstname,
                'lastName'  => $customerLastname,
                'document'  => $customerTaxvat ? $customerTaxvat : self::DEFAULT_CUSTOMER_TAXVAT,
                'email'     => $customerEmail ? $customerEmail : self::DEFAULT_CUSTOMER_EMAIL,
                'phone'     => $customerPhone,
            ),
        );

        try
        {
            $result = Mage::helper ('picpay')->api (Gamuza_PicPay_Helper_Data::API_PAYMENTS_URL, $post, null, $order->getStoreId ());

            if (empty ($result) || !is_object ($result))
            {
                throw new Exception (Mage::helper ('picpay')->__('Receveid an empty PICPAY transaction.'));
            }

            $payment->setData (Gamuza_PicPay_Helper_Data::PAYMENT_ATTRIBUTE_PICPAY_URL, $result->paymentUrl)
                ->setData (Gamuza_PicPay_Helper_Data::PAYMENT_ATTRIBUTE_PICPAY_STATUS, Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_CREATED)
                ->save ()
            ;

            $transaction = Mage::getModel ('picpay/transaction')
                /* Params */
                ->setStoreId ($order->getStoreId ())
                ->setCustomerId ($order->getCustomerId ())
                ->setOrderId ($order->getId ())
                ->setOrderIncrementId ($order->getIncrementId ())
                ->setCallbackUrl ($callbackUrl)
                ->setReturUrl (new Zend_Db_Expr ('NULL'))
                ->setAmount (floatval ($order->getBaseGrandTotal ()))
                ->setBuyerEmail ($customerEmail)
                ->setMessage (new Zend_Db_Expr ('NULL'))
                ->setCreatedAt (date ('c'))
                /* Result */
                ->setExpiresAt ($result->expiresAt)
                ->setPaymentUrl ($result->paymentUrl)
                ->setQrcodeContent ($result->qrcode->content)
                ->setQrcodeBase64 ($result->qrcode->base64)
                ->setStatus (Gamuza_PicPay_Helper_Data::API_PAYMENT_STATUS_CREATED)
                ->setAuthorizationId (new Zend_Db_Expr ('NULL'))
                ->setCancellationId (new Zend_Db_Expr ('NULL'))
                ->save ()
            ;
        }
        catch (Exception $e)
        {
            Mage::log ($e->getMessage (), null, Gamuza_PicPay_Helper_Data::LOG, true);

            throw new Mage_Core_Exception (
                Mage::helper ('picpay')->__('There was an error in the PICPAY transaction. Please try again!')
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

    /**
     * Check whether payment method can be used
     *
     * @param Mage_Sales_Model_Quote|null $quote
     *
     * @return bool
     */
    public function isAvailable ($quote = null)
    {
        $isAvailable = parent::isAvailable ($quote);

        $isPDV = Mage::helper ('core')->isModuleEnabled ('Toluca_PDV')
            && !empty ($quote) && intval ($quote->getId ()) > 0
            && $quote->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_IS_PDV);

        return $isAvailable && $isPDV;
    }
}

