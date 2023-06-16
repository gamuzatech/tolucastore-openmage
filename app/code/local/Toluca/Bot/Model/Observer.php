<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Bot module observer
 */
class Toluca_Bot_Model_Observer
{
    const BOT_CHAT_LIFETIME = 86400;

    public function cleanExpiredChats()
    {
        /** @var $chats Toluca_Bot_Model_Mysql4_Chat_Collection */
        $chats = Mage::getModel('bot/chat')->getCollection()
            ->addFieldToFilter('status', array ('neq' => Toluca_Bot_Helper_Data::STATUS_ORDER))
        ;

        $chats->addFieldToFilter('updated_at', array('to'=>date("Y-m-d H:i:s", mktime(23, 59, 59) - self::BOT_CHAT_LIFETIME)));

        $chats->walk('delete');

        return $this;
    }
}

