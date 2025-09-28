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
}

