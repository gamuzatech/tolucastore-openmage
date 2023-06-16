<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2021 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

$installer = new Mage_Core_Model_Resource_Setup ('bot_setup');
$installer->startSetup ();

/**
 * Config
 */
Mage::getModel ('core/config')
    ->saveConfig (Toluca_Bot_Helper_Data::XML_PATH_BOT_BASIC_AUTH_ACTIVE,   '1')
    ->saveConfig (Toluca_Bot_Helper_Data::XML_PATH_BOT_BASIC_AUTH_USERNAME, Mage::helper ('bot')->uniqid ())
    ->saveConfig (Toluca_Bot_Helper_Data::XML_PATH_BOT_BASIC_AUTH_PASSWORD, Mage::helper ('bot')->uniqid ())
;

$installer->endSetup ();

