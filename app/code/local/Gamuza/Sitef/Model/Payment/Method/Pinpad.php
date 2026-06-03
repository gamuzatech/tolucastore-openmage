<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Sitef_Model_Payment_Method_Pinpad extends Mage_Payment_Model_Method_Abstract
{
    const CODE = 'gamuza_sitef_pinpad';

    protected $_code = self::CODE;

    protected $_isGateway = true;
    protected $_canOrder = true;

    protected $_formBlockType = 'sitef/payment_form_pinpad';
    protected $_infoBlockType = 'sitef/payment_info_pinpad';

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
        try
        {
            $transaction = Mage::getModel ('sitef/pinpad_transaction')
                /* Params */
                ->setStoreId ($order->getStoreId ())
                ->setCustomerId ($order->getCustomerId ())
                ->setOrderId ($order->getId ())
                ->setOrderIncrementId ($order->getIncrementId ())
                ->setPaymentAmount (floatval ($order->getBaseGrandTotal ()))
                ->setMessage (new Zend_Db_Expr ('NULL'))
                ->setCreatedAt (date ('c'))
                /* Result */
                ->setStatus (Gamuza_Sitef_Helper_Data::API_PAYMENT_STATUS_CREATED)
                ->save ()
            ;

            if (Mage::helper ('core')->isModuleEnabled ('Toluca_PDV')
                && boolval ($order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_IS_PDV)))
            {
                $transaction->setCustomerId ($order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_CUSTOMER_ID))
                    ->setOperatorId ($order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_OPERATOR_ID))
                    ->save ()
                ;
            }

            $payment->setData (Gamuza_Sitef_Helper_Data::PAYMENT_ATTRIBUTE_SITEF_TRANS_ID, $transaction->getId ())
                ->setTransactionId ($transaction->getId ())
                ->setIsTransactionClosed (0)
                ->save ()
            ;
        }
        catch (Exception $e)
        {
            Mage::log ($e->getMessage (), null, Gamuza_Sitef_Helper_Data::LOG, true);

            throw new Mage_Core_Exception (
                Mage::helper ('sitef')->__('There was an error in the SITEF transaction. Please try again!')
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

        $isPDV = !empty ($quote) && intval ($quote->getId ()) > 0
            && Mage::helper ('core')->isModuleEnabled ('Toluca_PDV')
            && boolval ($quote->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_IS_PDV));

        return $isAvailable && $isPDV;
    }
}

