<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

trait Toluca_Bot_Trait_Chat
{
    public function messageAction ()
    {
        if (Mage::getStoreConfigFlag (Toluca_Bot_Helper_Data::XML_PATH_BOT_BASIC_AUTH_ACTIVE))
        {
            if (!isset ($_SERVER ['PHP_AUTH_USER']) || !isset ($_SERVER ['PHP_AUTH_PW']))
            {
                header ('WWW-Authenticate: Basic realm="Authentication Required"');
                header ('HTTP/1.0 401 Unauthorized');

                die (__('Unauthorized'));
            }

            $authUser = $_SERVER ['PHP_AUTH_USER'];
            $authPass = $_SERVER ['PHP_AUTH_PW'];

            $username = Mage::getStoreConfig (Toluca_Bot_Helper_Data::XML_PATH_BOT_BASIC_AUTH_USERNAME);
            $password = Mage::getStoreConfig (Toluca_Bot_Helper_Data::XML_PATH_BOT_BASIC_AUTH_PASSWORD);

            if (strcmp ($authUser, $username) || strcmp ($authPass, $password))
            {
                header ('HTTP/1.0 403 Forbidden');

                die (__('Forbidden'));
            }
        }

        ini_set ('always_populate_raw_post_data', '-1');

        $rawData = $this->getRequest ()->getRawBody ();

        if (empty ($rawData)) die (__('Invalid Data'));

        $jsonData = json_decode ($rawData, true);

        $result = Mage::getModel ('bot/chat_api')->message(
            $jsonData ['botType'],
            $jsonData ['from'],
            $jsonData ['to'],
            $jsonData ['senderName'],
            $jsonData ['senderMessage'],
        );

        $this->getResponse()
            ->clearHeaders ()
            ->setHeader ('Content-type', 'application/json')
            ->setBody (Mage::helper ('core')->jsonEncode ($result))
        ;

        return $this;
    }
}

