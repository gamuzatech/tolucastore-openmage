<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Mobile_Helper_Data extends Mage_Core_Helper_Abstract
{
    const ORDER_ATTRIBUTE_IS_APP         = 'is_app';
    const ORDER_ATTRIBUTE_IS_COMANDA     = 'is_comanda';
    const ORDER_ATTRIBUTE_IS_PRINTED     = 'is_printed';

    const ORDER_ATTRIBUTE_CUSTOMER_STARS = 'customer_stars';
    const ORDER_ATTRIBUTE_CUSTOMER_INFO_CODE = 'customer_info_code';
    const ORDER_ATTRIBUTE_STORE_INFO_CODE = 'store_info_code';

    const ORDER_ATTRIBUTE_USER_AGENT = 'user_agent';

    const XML_GLOBAL_PAYMENT_MACHINE_TYPES = 'global/payment/machine/types';

    const XML_PATH_API_MOBILE_STORE_VIEW = 'api/mobile/store_view';

    const XML_PATH_DEFAULT_EMAIL_PREFIX = 'customer/create_account/email_prefix';

    const XML_PATH_GENERAL_STORE_INFORMATION_CODE = 'general/store_information/code';
    const XML_PATH_GENERAL_STORE_INFORMATION_NAME = 'general/store_information/name';
    const XML_PATH_GENERAL_STORE_INFORMATION_LOGO = 'general/store_information/logo';

    const DEFAULT_CUSTOMER_TAXVAT = '02788178824';

    public function getRemoteIp ()
    {
        return $_SERVER ['HTTP_X_REMOTE_IP'] ?? $_SERVER ['HTTP_X_LOCAL_IP']
            ?? Mage::helper ('core/http')->getRemoteAddr (false);
    }

    public function getUserAgent ()
    {
        return $_SERVER ['HTTP_X_USER_AGENT']
            ?? Mage::helper ('core/http')->getHttpUserAgent (false);
    }

    public function isComanda ()
    {
        return strpos ($_SERVER ['HTTP_USER_AGENT'], 'TolucaStoreComanda') !== false;
    }
}

