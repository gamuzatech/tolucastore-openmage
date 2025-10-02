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

    const ORDER_ATTRIBUTE_IS_ZAP = 'is_zap';

    const BOT_TYPE_ADMIN  = 'admin';
    const BOT_TYPE_URA    = 'ura';
    const BOT_TYPE_OPENAI = 'openai';
    const BOT_TYPE_GEMINI = 'gemini';
    const BOT_TYPE_CLAUDE = 'claude';

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

    const CATEGORY_ID_LENGTH = 5;
    const PRODUCT_ID_LENGTH  = 5;
    const OPTION_ID_LENGTH   = 5;
    const VALUE_ID_LENGTH    = 5;
    const SHIPPING_ID_LENGTH = 5;
    const PAYMENT_ID_LENGTH  = 5;
    const CCTYPE_ID_LENGTH   = 5;
    const QUANTITY_LENGTH    = 5;
    const ORDER_ID_LENGTH    = 20;

    const COMMAND_ZERO = '0';
    const COMMAND_ONE  = '1';
    const COMMAND_OK   = 'ok';

    const DEFAULT_CUSTOMER_EMAIL  = 'store@toluca.com.br';
    const DEFAULT_CUSTOMER_TAXVAT = '02788178824';

    const XML_PATH_BOT_INFORMATION_STORE_URL    = 'bot/information/store_url';
    const XML_PATH_BOT_INFORMATION_WHATSAPP_URL = 'bot/information/whatsapp_url';

    const XML_PATH_BOT_BASIC_AUTH_ACTIVE   = 'bot/basic_auth/active';
    const XML_PATH_BOT_BASIC_AUTH_USERNAME = 'bot/basic_auth/username';
    const XML_PATH_BOT_BASIC_AUTH_PASSWORD = 'bot/basic_auth/password';

    const XML_PATH_BOT_NOTIFICATION_ORDER = 'bot/notification/order';

    public function getRemoteIp ()
    {
        return $_SERVER ['HTTP_X_REMOTE_IP'] ?? $_SERVER ['HTTP_X_LOCAL_IP']
            ?? Mage::helper ('core/http')->getRemoteAddr (false);
    }

    public function headers ()
    {
        $result = array(
            'botType'       => $_SERVER ['HTTP_X_BOT_TYPE'],
            'from'          => $_SERVER ['HTTP_X_SENDER_FROM'],
            'to'            => $_SERVER ['HTTP_X_SENDER_TO'],
            'senderName'    => $_SERVER ['HTTP_X_SENDER_NAME'],
            'senderMessage' => $_SERVER ['HTTP_X_SENDER_MESSAGE'],
        );

        return $result;
    }

    public function uniqid ()
    {
        return hash ('sha512', uniqid (rand (), true));
    }
}

