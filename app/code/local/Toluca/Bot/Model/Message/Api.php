<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Message API
 */
class Toluca_Bot_Model_Message_Api extends Toluca_Bot_Model_Api_Resource_Abstract
{
    public function items ()
    {
        $result = array ();

        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $headers = Mage::helper ('bot')->headers ();

        list ($botType, $from, $to, $senderName, $senderMessage) = array_values ($headers);

        $from = preg_replace ('[\D]', null, $from);
        $to   = preg_replace ('[\D]', null, $to);

        $collection = Mage::getModel ('bot/chat')->getCollection ()
            ->addFieldToFilter ('is_active', array ('eq' => true))
            ->addFieldToFilter ('store_id',  array ('eq' => $storeId))
            ->addFieldToFilter ('quote_id',  array ('gt' => 0))
            /*
            ->addFieldToFilter ('order_id',  array ('eq' => 0))
            */
            ->addFieldToFilter ('type_id',   array ('eq' => $botType))
            ->addFieldToFilter ('number',    array ('eq' => $from))
            ->addFieldToFilter ('phone',     array ('eq' => $to))
            /*
            ->addFieldToFilter ('status',    array ('neq' => Toluca_Bot_Helper_Data::STATUS_ORDER))
            */
        ;

        $collection->getSelect ()
            ->order ('created_at DESC')
            ->limit (1)
        ;

        if ($collection->count () > 0)
        {
            $chat = $collection->getFirstItem ();

            $collection = Mage::getModel ('bot/message')->getCollection ()
                ->addFieldToFilter ('chat_id', array ('eq' => $chat->getId ()))
            ;

            foreach ($collection as $message)
            {
                $result [] = array(
                    'entity_id'  => intval ($message->getId ()),
                    'chat_id'    => intval ($message->getChatId ()),
                    'bot_type'   => $message->getBotType (),
                    'type_id'    => $message->getTypeId (),
                    'remote_ip'  => $message->getRemoteIp (),
                    'email'      => $message->getEmail (),
                    'number'     => $message->getNumber (),
                    'firstname'  => $message->getFirstname (),
                    'lastname'   => $message->getLastname (),
                    'message'    => $message->getMessage (),
                    'phone'      => $message->getPhone (),
                    'created_at' => $message->getCreatedAt (),
                );
            }
        }

        return $result;
    }

    public function add ($type, $text)
    {
        $result = null;

        Mage::app ()->setCurrentStore (Mage_Core_Model_App::DISTRO_STORE_ID);

        $storeId = Mage::app ()->getStore ()->getId ();

        $headers = Mage::helper ('bot')->headers ();

        list ($botType, $from, $to, $senderName, $senderMessage) = array_values ($headers);

        $quote = $this->_getQuote ($botType, $from, $to, $senderName, $senderMessage);

        $from = preg_replace ('[\D]', null, $from);
        $to   = preg_replace ('[\D]', null, $to);

        $collection = Mage::getModel ('bot/chat')->getCollection ()
            ->addFieldToFilter ('is_active', array ('eq' => true))
            ->addFieldToFilter ('store_id',  array ('eq' => $storeId))
            ->addFieldToFilter ('quote_id',  array ('gt' => 0))
            /*
            ->addFieldToFilter ('order_id',  array ('eq' => 0))
            */
            ->addFieldToFilter ('type_id',   array ('eq' => $botType))
            ->addFieldToFilter ('number',    array ('eq' => $from))
            ->addFieldToFilter ('phone',     array ('eq' => $to))
            /*
            ->addFieldToFilter ('status',    array ('neq' => Toluca_Bot_Helper_Data::STATUS_ORDER))
            */
        ;

        $collection->getSelect ()
            ->order ('created_at DESC')
            ->limit (1)
        ;

        $chat = $collection->getFirstItem ();

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

        $remoteIp = Mage::helper ('bot')->getRemoteIp ();

        if (!$collection->count ())
        {
            $chat = Mage::getModel ('bot/chat')
                ->setIsActive (true)
                ->setTypeId ($botType)
                ->setStoreId ($storeId)
                ->setQuoteId ($quote->getId ())
                ->setNumber ($from)
                ->setPhone ($to)
                ->setFirstname ($senderName [0])
                ->setLastname ($senderName [1])
                ->setRemoteIp ($remoteIp)
                ->setEmail (self::DEFAULT_CUSTOMER_EMAIL)
                ->setStatus (Toluca_Bot_Helper_Data::STATUS_CATEGORY)
                ->setCreatedAt (date ('c'))
                ->setUpdatedAt (date ('c'))
                ->save ()
            ;
        }

        $message = $this->_saveMessage ($text, $chat, $type);

        if (!strcmp ($type, Toluca_Bot_Helper_Data::MESSAGE_TYPE_ANSWER)
            && !strcmp ($chat->getStatus (), Toluca_Bot_Helper_Data::STATUS_ORDER)
            && $chat->getOrderId () && $chat->getIsActive ())
        {
            $chat->setIsActive (false)
                ->setUpdatedAt (date ('c'))
                ->save ()
            ;
        }

        return intval ($message->getId ());
    }
}

