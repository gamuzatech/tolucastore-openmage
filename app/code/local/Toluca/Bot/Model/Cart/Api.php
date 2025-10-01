<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Cart API
 */
class Toluca_Bot_Model_Cart_Api extends Toluca_Bot_Model_Api_Resource_Abstract
{
    public function info ()
    {
        $result = null;

        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $headers = Mage::helper ('bot')->headers ();

        list ($botType, $from, $to, $senderName, $senderMessage) = array_values ($headers);

        try
        {
            $quote = $this->_getQuote ($botType, $from, $to, $senderName, $senderMessage);

            $result = $this->_getCartReview ($quote->getId (), $storeId) . PHP_EOL . PHP_EOL;
        }
        catch (Mage_Api_Exception $e)
        {
            $result = Mage::helper ('bot')->__('Obs: %s', $e->getCustomMessage ()) . PHP_EOL . PHP_EOL;
        }

        return $result;
    }

    public function review ()
    {
        $result = null;

        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $headers = Mage::helper ('bot')->headers ();

        list ($botType, $from, $to, $senderName, $senderMessage) = array_values ($headers);

        try
        {
            $quote = $this->_getQuote ($botType, $from, $to, $senderName, $senderMessage);

            $result = $this->_getCheckoutReview ($quote->getId (), $storeId) . PHP_EOL . PHP_EOL;
        }
        catch (Mage_Api_Exception $e)
        {
            $result = Mage::helper ('bot')->__('Obs: %s', $e->getCustomMessage ()) . PHP_EOL . PHP_EOL;
        }

        return $result;
    }

    public function order ()
    {
        $result = null;

        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $headers = Mage::helper ('bot')->headers ();

        list ($botType, $from, $to, $senderName, $senderMessage) = array_values ($headers);

        Mage::app ()->getStore ()->setConfig (Mage_Checkout_Helper_Data::XML_PATH_GUEST_CHECKOUT, '1');

        try
        {
            $quote = $this->_getQuote ($botType, $from, $to, $senderName, $senderMessage);

            $incrementId = Mage::getModel ('checkout/cart_api')->createOrder ($quote->getId (), $storeId);

            $storeName = Mage::getStoreConfig (Mage_Core_Model_Store::XML_PATH_STORE_STORE_NAME);

            $order = Mage::getModel ('sales/order')->loadByIncrementId ($incrementId);

            $result .= Mage::helper ('bot/message')->getYourOrderNumberText ($order) . PHP_EOL . PHP_EOL
                . Mage::helper ('bot/message')->getOrderInformationText ($order)
                . Mage::helper ('bot/message')->getThankYouForShoppingText ($storeName) . PHP_EOL . PHP_EOL
                . Mage::helper ('bot/message')->getBuyThroughTheAppText ()
            ;

            $chat = $this->_getChat ($botType, $from, $to, $senderName, $senderMessage);

            $chat->setStatus (Toluca_Bot_Helper_Data::STATUS_ORDER)
                ->setOrderId ($order->getId ())
                ->setUpdatedAt (date ('c'))
                ->save ()
            ;

            $quote->delete (); // discard
        }
        catch (Mage_Api_Exception $e)
        {
            $result = Mage::helper ('bot')->__('Obs: %s', $e->getCustomMessage ()) . PHP_EOL . PHP_EOL;
        }

        return $result;
    }
}

