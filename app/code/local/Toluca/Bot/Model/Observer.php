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

    public function basicMagentoApiInfo (Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent ();
        $info = $event->getInfo ();

        $botNotificationStatus = Mage::getStoreConfig (Toluca_Bot_Helper_Data::XML_PATH_BOT_NOTIFICATION_STATUS);

        $info = array_replace_recursive ($info, array(
            'config' => array(
                'bot' => array(
                    'notification' => array(
                        'status' => $botNotificationStatus ? explode (',', $botNotificationStatus) : array (),
                    ),
                ),
            ),
        ));

        $event->setInfo ($info);
    }

    public function cleanExpiredChats()
    {
        /** @var $chats Toluca_Bot_Model_Mysql4_Chat_Collection */
        $chats = Mage::getModel('bot/chat')->getCollection()
            ->addFieldToFilter('is_active', array ('eq' => true))
            ->addFieldToFilter('status', array ('neq' => Toluca_Bot_Helper_Data::STATUS_ORDER))
        ;

        $chats->addFieldToFilter('updated_at', array('to'=>date("Y-m-d H:i:s", mktime(23, 59, 59) - self::BOT_CHAT_LIFETIME)));

        $chats->walk('delete');

        return $this;
    }

    public function salesOrderPlaceAfter ($observer)
    {
        $event = $observer->getEvent ();
        $order = $event->getOrder();

        if (Mage::getStoreConfigFlag (Toluca_Bot_Helper_Data::XML_PATH_BOT_NOTIFICATION_ORDER))
        {
            $order->setData (Toluca_Bot_Helper_Data::ORDER_ATTRIBUTE_IS_ZAP, true)->save ();
        }
    }
}

