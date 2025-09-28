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
            ->addFieldToFilter ('store_id',  array ('eq' => $storeId))
            ->addFieldToFilter ('quote_id',  array ('gt' => 0))
            ->addFieldToFilter ('order_id',  array ('eq' => 0))
            ->addFieldToFilter ('type_id',   array ('eq' => $botType))
            ->addFieldToFilter ('number',    array ('eq' => $from))
            ->addFieldToFilter ('phone',     array ('eq' => $to))
            ->addFieldToFilter ('status',    array ('neq' => Toluca_Bot_Helper_Data::STATUS_ORDER))
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
}

