<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * API Resource Abstract
 */
class Toluca_Bot_Model_Api_Resource_Abstract extends Mage_Api_Model_Resource_Abstract
{
    const CATEGORY_ID_LENGTH = Toluca_Bot_Helper_Data::CATEGORY_ID_LENGTH;
    const PRODUCT_ID_LENGTH  = Toluca_Bot_Helper_Data::PRODUCT_ID_LENGTH;
    const OPTION_ID_LENGTH   = Toluca_Bot_Helper_Data::OPTION_ID_LENGTH;
    const VALUE_ID_LENGTH    = Toluca_Bot_Helper_Data::VALUE_ID_LENGTH;
    const SHIPPING_ID_LENGTH = Toluca_Bot_Helper_Data::SHIPPING_ID_LENGTH;
    const PAYMENT_ID_LENGTH  = Toluca_Bot_Helper_Data::PAYMENT_ID_LENGTH;
    const CCTYPE_ID_LENGTH   = Toluca_Bot_Helper_Data::CCTYPE_ID_LENGTH;
    const QUANTITY_LENGTH    = Toluca_Bot_Helper_Data::QUANTITY_LENGTH;

    const COMMAND_ZERO = Toluca_Bot_Helper_Data::COMMAND_ZERO;
    const COMMAND_ONE  = Toluca_Bot_Helper_Data::COMMAND_ONE;
    const COMMAND_OK   = Toluca_Bot_Helper_Data::COMMAND_OK;

    const DEFAULT_CUSTOMER_EMAIL  = Toluca_Bot_Helper_Data::DEFAULT_CUSTOMER_EMAIL;
    const DEFAULT_CUSTOMER_TAXVAT = Toluca_Bot_Helper_Data::DEFAULT_CUSTOMER_TAXVAT;
}

