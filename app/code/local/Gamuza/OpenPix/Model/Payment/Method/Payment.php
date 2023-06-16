<?php
/**
 * @package     Gamuza_OpenPix
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_OpenPix_Model_Payment_Method_Payment extends Mage_Payment_Model_Method_Abstract
{
    const CODE = 'gamuza_openpix_payment';

    protected $_code = self::CODE;

    protected $_canOrder = true;

    protected $_formBlockType = 'openpix/payment_form_payment';
    protected $_infoBlockType = 'openpix/payment_info_payment';

    const DEFAULT_CUSTOMER_EMAIL  = 'store@toluca.com.br';
    const DEFAULT_CUSTOMER_TAXVAT = '02788178824';
    const EXPIRES_IN_SECONDS = 900;

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
        $correlationId = Mage::helper ('openpix')->uuid_v4 ();
        $orderAmount   = intval ($order->getBaseGrandTotal () * 100);

        $storeName = Mage::getStoreConfig (Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME);

        $customerName   = sprintf ("%s %s", $order->getCustomerFirstname (), $order->getCustomerLastname ());
        $customerEmail  = $order->getCustomerEmail ();
        $customerPhone  = preg_replace ('[\D]', '', $order->getBillingAddress ()->getCellphone ());
        $customerTaxvat = preg_replace ('[\D]', '', $order->getCustomerTaxvat ());

        $post = array(
            'correlationID' => $correlationId,
            'value'     => $orderAmount,
            'comment'   => sprintf ('%s %s-%s (%s)',
                Mage::helper ('openpix')->__('Order'),
                $order->getIncrementId (), $order->getProtectCode(),
                $storeName,
            ),
            'expiresIn' => self::EXPIRES_IN_SECONDS,
            'customer' => array(
                'name'  => $customerName,
                'email' => $customerEmail ? $customerEmail : self::DEFAULT_CUSTOMER_EMAIL,
                'phone' => $customerPhone,
                'taxID' => $customerTaxvat ? $customerTaxvat : self::DEFAULT_CUSTOMER_TAXVAT,
            ),
            'additionalInfo' => array(
                array(
                    'key'   => Mage::helper ('openpix')->__('Store'),
                    'value' => $storeName,
                ),
                array(
                    'key'   => Mage::helper ('openpix')->__('Order'),
                    'value' => sprintf ('%s-%s', $order->getIncrementId (), $order->getProtectCode ()),
                ),
            ),
        );

        try
        {
            $result = Mage::helper ('openpix')->api (Gamuza_OpenPix_Helper_Data::API_PAYMENT_CHARGE_URL, $post, null, $order->getStoreId ());

            if (empty ($result) || !is_object ($result))
            {
                throw new Exception (Mage::helper ('openpix')->__('Receveid an empty OPENPIX transaction.'));
            }

            $payment->setData (Gamuza_OpenPix_Helper_Data::PAYMENT_ATTRIBUTE_OPENPIX_URL, $result->charge->paymentLinkUrl)
                ->setData (Gamuza_OpenPix_Helper_Data::PAYMENT_ATTRIBUTE_OPENPIX_STATUS, Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_ACTIVE)
                ->setData (Gamuza_OpenPix_Helper_Data::PAYMENT_ATTRIBUTE_OPENPIX_TRANSACTION_ID, $result->charge->transactionID)
                ->setData (Gamuza_OpenPix_Helper_Data::PAYMENT_ATTRIBUTE_OPENPIX_CORRELATION_ID, $correlationId)
                ->save ()
            ;

            $transaction = Mage::getModel ('openpix/transaction')
                ->setStatus (Gamuza_OpenPix_Helper_Data::API_PAYMENT_STATUS_ACTIVE)
                ->setMessage (new Zend_Db_Expr ('NULL'))
                ->setCreatedAt (date ('c'))
                /* Params */
                ->setStoreId ($order->getStoreId ())
                ->setCustomerId ($order->getCustomerId ())
                ->setOrderId ($order->getId ())
                ->setOrderIncrementId ($order->getIncrementId ())
                ->setCorrelationId ($correlationId)
                ->setAmount ($orderAmount)
                ->setExpiresIn (self::EXPIRES_IN_SECONDS)
                /* Result */
                ->setTransactionId ($result->charge->transactionID)
                ->setPaymentLinkId ($result->charge->paymentLinkID)
                ->setPaymentLinkUrl ($result->charge->paymentLinkUrl)
                ->setQrcodeImageUrl ($result->charge->qrCodeImage)
                ->setBrcodeUrl ($result->charge->brCode)
                ->setPixKey ($result->charge->pixKey)
                ->setGlobalId ($result->charge->globalID)
                ->save ()
            ;
        }
        catch (Exception $e)
        {
            Mage::log ($e->getMessage (), null, Gamuza_OpenPix_Helper_Data::LOG, true);

            throw new Mage_Core_Exception (
                Mage::helper ('openpix')->__('There was an error in the OPENPIX transaction. Please try again!')
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

