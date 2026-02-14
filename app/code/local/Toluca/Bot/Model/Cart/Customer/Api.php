<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Cart Customer API
 */
class Toluca_Bot_Model_Cart_Customer_Api extends Toluca_Bot_Model_Api_Resource_Abstract
{
    public function address ($streetName, $streetNumber = null, $streetDistrict = 'xxxxxx')
    {
        $result = null;

        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $headers = Mage::helper ('bot')->headers ();

        list ($botType, $from, $to, $senderName, $senderMessage) = array_values ($headers);

        try
        {
            $quote = $this->_getQuote ($botType, $from, $to, $senderName, $senderMessage);

            if (!empty ($senderName))
            {
                $senderName = explode (' ', $senderName, 2);
            }

            if (is_array ($senderName) && count ($senderName) == 1)
            {
                $senderName [1] = '------';
            }

            if (!$senderName || !is_array ($senderName) || count ($senderName) != 2)
            {
                $senderName = array(
                    0 => Mage::helper ('bot')->__('Firstname'),
                    1 => Mage::helper ('bot')->__('Lastname'),
                );
            }

            $streetNumber   = !empty ($streetNumber) ? $streetNumber : '------';
            $streetDistrict = !empty ($streetDistrict) ? $streetDistrict : '------';

            $shippingPostcode = preg_replace ('[\D]', null, Mage::getStoreConfig ('shipping/origin/postcode', $storeId));

            $from = preg_replace ('[\D]', null, $from);

            Mage::getModel ('checkout/cart_customer_api')->setAddresses ($quote->getId (), array(
                array(
                    'mode'       => 'billing',
                    'firstname'  => $senderName [0],
                    'lastname'   => $senderName [1],
                    'street'     => array ($streetName, $streetNumber, null, $streetDistrict),
                    'city'       => Mage::getStoreConfig ('shipping/origin/city', $storeId),
                    'region'     => Mage::getStoreConfig ('shipping/origin/region_id', $storeId),
                    'country_id' => Mage::getStoreConfig ('shipping/origin/country_id', $storeId),
                    'postcode'   => $shippingPostcode,
                    'cellphone'  => substr ($from, -13),
                    'use_for_shipping' => 1,
                )
            ), $storeId);

            $result = Mage::helper ('bot/message')->getCustomerAddressSetToCartText () . PHP_EOL . PHP_EOL;
        }
        catch (Mage_Api_Exception $e)
        {
            $result = Mage::helper ('bot')->__('Obs: %s', $e->getCustomMessage ()) . PHP_EOL . PHP_EOL;
        }

        return $result;
    }
}

