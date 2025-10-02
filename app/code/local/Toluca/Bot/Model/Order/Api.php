<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Order API
 */
class Toluca_Bot_Model_Order_Api extends Toluca_Bot_Model_Api_Resource_Abstract
{
    public function items ()
    {
        $result = null;

        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $headers = Mage::helper ('bot')->headers ();

        list ($botType, $from, $to, $senderName, $senderMessage) = array_values ($headers);

        $from = preg_replace ('[\D]', null, $from);
        $to   = preg_replace ('[\D]', null, $to);

        $collection = Mage::getModel ('sales/order')->getCollection ()
            ->addFieldToFilter ('store_id',  array ('eq' => $storeId))
            ->addFieldToFilter ('quote_id',  array ('gt' => 0))
            ->addFieldToFilter ('is_bot',    array ('eq' => 1))
            ->addFieldToFilter ('bot_type',  array ('eq' => $botType))
            /*
            ->addFieldToFilter ('state',     array ('neq' => Mage_Sales_Model_Order::STATE_HOLDED))
            */
            ->addFieldToFilter ('customer_cellphone', array ('eq' => $from))
        ;

        foreach ($collection as $order)
        {
            $strLen = self::ORDER_ID_LENGTH - strlen ($order->getIncrementId ());
            $strPad = str_pad ("", $strLen, ' ', STR_PAD_RIGHT);

            $orderStatusLabel = Mage::getModel ('sales/order_status')->load ($order->getStatus ())->getLabel ();

            $result .= sprintf ('%s%s*%s*', $order->getIncrementId (), $strPad, $orderStatusLabel) . PHP_EOL;
        }

        return $result;
    }
}

