<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Config API
 */
class Toluca_Bot_Model_Config_Api extends Mage_Api_Model_Resource_Abstract
{
    public function basicAuth ($active, $username, $password)
    {
        if (empty ($active) || empty ($username) || empty ($password))
        {
            $this->_fault ('data_invalid');
        }

        Mage::getModel ('core/config')->saveConfig (Toluca_Bot_Helper_Data::XML_PATH_BOT_BASIC_AUTH_ACTIVE, intval ($active))
            ->saveConfig (Toluca_Bot_Helper_Data::XML_PATH_BOT_BASIC_AUTH_USERNAME, $username)
            ->saveConfig (Toluca_Bot_Helper_Data::XML_PATH_BOT_BASIC_AUTH_PASSWORD, $password);

        return true;
    }
}

