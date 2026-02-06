<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Cart Payment API
 */
class Toluca_Bot_Model_Cart_Payment_Api extends Toluca_Bot_Model_Api_Resource_Abstract
{
    public function items ($shippingId = null, $shippingName = null)
    {
        $result = Mage::helper ('bot/message')->getEnterPaymentMethodText () . PHP_EOL . PHP_EOL;

        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $headers = Mage::helper ('bot')->headers ();

        list ($botType, $from, $to, $senderName, $senderMessage) = array_values ($headers);

        try
        {
            $quote = $this->_getQuote ($botType, $from, $to, $senderName, $senderMessage);

            $paymentMethods = Mage::getModel ('bot/checkout_cart_payment_api')->getPaymentMethodsList ($quote->getId (), $storeId);

            if (count ($paymentMethods) > 0)
            {
                foreach ($this->_paymentMethods as $_id => $_method)
                {
                    foreach ($paymentMethods as $method)
                    {
                        if (!strcmp ($method ['code'], $_method))
                        {
                            $strLen = self::PAYMENT_ID_LENGTH - strlen ($_id);
                            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

                            $paymentPrice = Mage::helper ('core')->currency ($quote->getBaseGrandTotal (), true, false);

                            $paymentLabelId    = ' paymentId: ';
                            $paymentLabelName  = ' paymentName: ';
                            $paymentLabelPrice = ' paymentPrice: ';

                            $result .= sprintf ("%s*%s*%s%s%s %s*%s*", $paymentLabelId, $_id, $strPad, $paymentLabelName, $method ['title'], $paymentLabelPrice, $paymentPrice) . PHP_EOL;

                            switch ($_method)
                            {
                                case 'machineondelivery':
                                {
                                    $result .= PHP_EOL . $this->_getCardList ($quote->getId (), $storeId, true) . PHP_EOL;

                                    break;
                                }
                                case 'gamuza_pagcripto_payment':
                                {
                                    $result .= PHP_EOL . $this->_getCriptoList ($quote->getId (), $storeId, true) . PHP_EOL;

                                    break;
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                $result = Mage::helper ('bot/message')->getNoPaymentMethodFoundText () . PHP_EOL . PHP_EOL;
            }
        }
        catch (Mage_Api_Exception $e)
        {
            $result = Mage::helper ('bot')->__('Obs: %s', $e->getCustomMessage ()) . PHP_EOL . PHP_EOL;
        }

        return $result;
    }

    public function set ($paymentId, $paymentName = null, $paymentChange = null, $paymentCcTypeId = null, $paymentCriptoTypeId = null, $shippingId = null, $shippingName = null)
    {
        $result = null;

        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $headers = Mage::helper ('bot')->headers ();

        list ($botType, $from, $to, $senderName, $senderMessage) = array_values ($headers);

        try
        {
            $quote = $this->_getQuote ($botType, $from, $to, $senderName, $senderMessage);

            $paymentMethods = Mage::getModel ('bot/checkout_cart_payment_api')->getPaymentMethodsList ($quote->getId (), $storeId);

            foreach ($paymentMethods as $id => $method)
            {
                if (!in_array ($method ['code'], $this->_paymentMethods))
                {
                    unset ($paymentMethods [$id]);
                }
            }

            foreach ($paymentMethods as $method)
            {
                if (!strcmp ($method ['title'], $paymentName))
                {
                    foreach ($this->_paymentMethods as $id => $code)
                    {
                        if (!strcmp ($method ['code'], $code))
                        {
                            $paymentId = $id;

                            break 2;
                        }
                    }
                }
            }

            if (!empty ($this->_paymentMethods [$paymentId]) && $this->_getAllowedPayment ($paymentMethods, $paymentId))
            {
                switch ($this->_paymentMethods [$paymentId])
                {
                    case 'cashondelivery':
                    {
                        $paymentData = array(
                            'method'      => 'cashondelivery',
                            'change_type' => $paymentChange ? '1' : '0',
                            'cash_amount' => $paymentChange,
                        );

                        break;
                    }
                    case 'machineondelivery':
                    {
                        if (!empty ($this->_paymentCcTypes [$paymentCcTypeId]) && $this->_getAllowedCcType ($paymentMethods, $paymentCcTypeId))
                        {
                            $paymentData = array(
                                'method'  => 'machineondelivery',
                                'cc_type' => $this->_paymentCcTypes [$paymentCcTypeId],
                            );
                        }
                        else
                        {
                            $errorMsg = Mage::helper('payment')->__('Credit card type is not allowed for this payment method.');

                            throw new Mage_Api_Exception (405, $errorMsg);
                        }

                        break;
                    }
                    case 'gamuza_pagcripto_payment':
                    {
                        if (!empty ($this->_paymentCriptoTypes [$paymentCriptoTypeId]) && $this->_getAllowedCriptoType ($paymentMethods, $paymentCriptoTypeId))
                        {
                            $paymentData = array(
                                'method'  => 'gamuza_pagcripto_payment',
                                'cc_type' => $this->_paymentCriptoTypes [$paymentCriptoTypeId],
                            );
                        }
                        else
                        {
                            $errorMsg = Mage::helper('payment')->__('Credit card type is not allowed for this payment method.');

                            throw new Mage_Api_Exception (405, $errorMsg);
                        }

                        break;
                    }
                    default:
                    {
                        $paymentData = array(
                            'method' => $this->_paymentMethods [$paymentId]
                        );

                        break;
                    }
                }

                Mage::getModel ('bot/checkout_cart_payment_api')->setPaymentMethod ($quote->getId (), $paymentData, $storeId);

                $result = Mage::helper ('bot/message')->getPaymentMethodSetToCartText () . PHP_EOL . PHP_EOL;
            }
            else
            {
                $result = Mage::helper ('bot/message')->getPaymentMethodNotSetToCartText () . PHP_EOL . PHP_EOL;
            }
        }
        catch (Mage_Api_Exception $e)
        {
            $result = Mage::helper ('bot')->__('Obs: %s', $e->getCustomMessage ()) . PHP_EOL . PHP_EOL;
        }

        return $result;
    }
}

