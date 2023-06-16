<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_Bot_Helper_Data extends Mage_Core_Helper_Abstract
{
    const CHAT_TABLE      = 'toluca_bot_chat';
    const CONTACT_TABLE   = 'toluca_bot_contact';
    const LOG_TABLE       = 'toluca_bot_log';
    const MESSAGE_TABLE   = 'toluca_bot_message';
    const PROMOTION_TABLE = 'toluca_bot_promotion';
    const QUEUE_TABLE     = 'toluca_bot_queue';

    const ORDER_ATTRIBUTE_IS_BOT = 'is_bot';
    const ORDER_ATTRIBUTE_BOT_TYPE = 'bot_type';

    const BOT_TYPE_SIGNAL   = 'signal';
    const BOT_TYPE_TELEGRAM = 'telegram';
    const BOT_TYPE_WHATSAPP = 'whatsapp';

    const MESSAGE_TYPE_QUESTION = 'question';
    const MESSAGE_TYPE_ANSWER   = 'answer';

    const QUEUE_STATUS_PENDING  = 'pending';
    const QUEUE_STATUS_SENDING  = 'sending';
    const QUEUE_STATUS_FINISHED = 'finished';
    const QUEUE_STATUS_CANCELED = 'canceled';
    const QUEUE_STATUS_STOPPED  = 'stopped';

    const STATUS_CATEGORY = 'category';
    const STATUS_PRODUCT  = 'product';
    const STATUS_OPTION   = 'option';
    const STATUS_VALUE    = 'value';
    const STATUS_BUNDLE   = 'bundle';
    const STATUS_SELECTION = 'selection';
    const STATUS_COMMENT  = 'comment';
    const STATUS_CART     = 'cart';
    const STATUS_ADDRESS  = 'address';
    const STATUS_SHIPPING = 'shipping';
    const STATUS_PAYMENT  = 'payment';
    const STATUS_PAYMENT_CASH = 'payment_cash';
    const STATUS_PAYMENT_MACHINE = 'payment_machine';
    const STATUS_PAYMENT_CRIPTO = 'payment_cripto';
    const STATUS_CHECKOUT = 'checkout';
    const STATUS_ORDER    = 'order';
    const STATUS_ZAP      = 'zap';

    const XML_PATH_BOT_INFORMATION_STORE_URL    = 'bot/information/store_url';
    const XML_PATH_BOT_INFORMATION_WHATSAPP_URL = 'bot/information/whatsapp_url';

    const XML_PATH_BOT_BASIC_AUTH_ACTIVE   = 'bot/basic_auth/active';
    const XML_PATH_BOT_BASIC_AUTH_USERNAME = 'bot/basic_auth/username';
    const XML_PATH_BOT_BASIC_AUTH_PASSWORD = 'bot/basic_auth/password';

    public function uniqid ()
    {
        return hash ('sha512', uniqid (rand (), true));
    }
}

