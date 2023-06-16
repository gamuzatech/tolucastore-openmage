<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Mobile_Helper_Data extends Mage_Core_Helper_Abstract
{
    const ORDER_ATTRIBUTE_IS_APP         = 'is_app';
    const ORDER_ATTRIBUTE_IS_PRINTED     = 'is_printed';
    const ORDER_ATTRIBUTE_CUSTOMER_STARS = 'customer_stars';
    const ORDER_ATTRIBUTE_CUSTOMER_INFO_CODE = 'customer_info_code';
    const ORDER_ATTRIBUTE_STORE_INFO_CODE = 'store_info_code';

    const XML_GLOBAL_PAYMENT_MACHINE_TYPES = 'global/payment/machine/types';

    const XML_PATH_API_MOBILE_STORE_VIEW = 'api/mobile/store_view';

    const XML_PATH_DEFAULT_EMAIL_PREFIX = 'customer/create_account/email_prefix';

    const DEFAULT_CUSTOMER_TAXVAT = '02788178824';
}

