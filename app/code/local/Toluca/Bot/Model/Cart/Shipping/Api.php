<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Cart Shipping API
 */
class Toluca_Bot_Model_Cart_Shipping_Api extends Toluca_Bot_Model_Api_Resource_Abstract
{
    public function items ()
    {
        $result = Mage::helper ('bot/message')->getEnterDeliveryMethodText () . PHP_EOL . PHP_EOL;

        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $headers = Mage::helper ('bot')->headers ();

        list ($botType, $from, $to, $senderName, $senderMessage) = array_values ($headers);

        try
        {
            $quote = $this->_getQuote ($botType, $from, $to, $senderName, $senderMessage);

            $quoteShippingAddress = $quote->getShippingAddress ();

            if ($quoteShippingAddress && $quoteShippingAddress->getId ())
            {
                $quoteShippingAddress->collectTotals ()->save ();
                $quoteShippingAddress->setCollectShippingRates (true)->save (); // FORCE RELOAD
            }

            $shippingMethods = Mage::getModel ('checkout/cart_shipping_api')->getShippingMethodsList ($quote->getId (), $storeId);

            if (count ($shippingMethods) > 0)
            {
                foreach ($this->_shippingMethods as $_id => $_method)
                {
                    foreach ($shippingMethods as $method)
                    {
                        if (!strcmp ($method ['code'], $_method))
                        {
                            $strLen = self::SHIPPING_ID_LENGTH - strlen ($_id);
                            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

                            $shippingPrice = Mage::helper ('core')->currency ($method ['price'], true, false);

                            $result .= sprintf ("*%s*%s%s *%s*", $_id, $strPad, $method ['method_title'], $shippingPrice) . PHP_EOL;
                        }
                    }
                }
            }
            else
            {
                $result = Mage::helper ('bot/message')->getNoDeliveryMethodFoundText () . PHP_EOL . PHP_EOL;
            }
        }
        catch (Mage_Api_Exception $e)
        {
            $result = Mage::helper ('bot')->__('Obs: %s', $e->getCustomMessage ()) . PHP_EOL . PHP_EOL;
        }

        return $result;
    }

    public function set ($shippingId, $shippingName = null)
    {
        $result = null;

        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $headers = Mage::helper ('bot')->headers ();

        list ($botType, $from, $to, $senderName, $senderMessage) = array_values ($headers);

        try
        {
            $quote = $this->_getQuote ($botType, $from, $to, $senderName, $senderMessage);

            $quoteShippingAddress = $quote->getShippingAddress ();

            if ($quoteShippingAddress && $quoteShippingAddress->getId ())
            {
                $quoteShippingAddress->collectTotals ()->save ();
                $quoteShippingAddress->setCollectShippingRates (true)->save (); // FORCE RELOAD
            }

            $shippingMethods = Mage::getModel ('checkout/cart_shipping_api')->getShippingMethodsList ($quote->getId (), $storeId);

            foreach ($shippingMethods as $id => $method)
            {
                if (!in_array ($method ['code'], $this->_shippingMethods))
                {
                    unset ($shippingMethods [$id]);
                }
            }

            if (!empty ($this->_shippingMethods [$shippingId]) && $this->_getAllowedShipping ($shippingMethods, $shippingId))
            {
                Mage::getModel ('checkout/cart_shipping_api')->setShippingMethod ($quote->getId (), $this->_shippingMethods [$shippingId], $storeId);

                $result = Mage::helper ('bot/message')->getDeliveryMethodSetToCartText () . PHP_EOL . PHP_EOL;
            }
            else
            {
                $result = Mage::helper ('bot/message')->getDeliveryMethodNotSetToCartText () . PHP_EOL . PHP_EOL;
            }
        }
        catch (Mage_Api_Exception $e)
        {
            $result = Mage::helper ('bot')->__('Obs: %s', $e->getCustomMessage ()) . PHP_EOL . PHP_EOL;
        }

        return $result;
    }
}

