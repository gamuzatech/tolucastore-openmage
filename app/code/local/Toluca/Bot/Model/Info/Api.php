<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Info API
 */
class Toluca_Bot_Model_Info_Api extends Mage_Api_Model_Resource_Abstract
{
    public function storeUrl ($url)
    {
        if (empty ($url))
        {
            $this->_fault ('data_invalid');
        }

        Mage::getModel ('core/config')->saveConfig (
            Toluca_Bot_Helper_Data::XML_PATH_BOT_INFORMATION_STORE_URL, $url
        );

        return true;
    }

    public function whatsappUrl ($url)
    {
        if (empty ($url))
        {
            $this->_fault ('data_invalid');
        }

        Mage::getModel ('core/config')->saveConfig (
            Toluca_Bot_Helper_Data::XML_PATH_BOT_INFORMATION_WHATSAPP_URL, $url
        );

        return true;
    }
}

