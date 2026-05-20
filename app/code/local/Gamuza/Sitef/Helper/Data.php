<?php
/**
 * @package     Gamuza_Sitef
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Sitef_Helper_Data extends Mage_Core_Helper_Abstract
{
    const API_PAYMENT_STATUS_CREATED     = 'created';
    const API_PAYMENT_STATUS_UPDATED     = 'updated';
    const API_PAYMENT_STATUS_AUTHORIZED  = 'authorized';
    const API_PAYMENT_STATUS_CANCELED    = 'canceled';

    const API_PAYMENT_METHOD_NONE           = -1;
    const API_PAYMENT_METHOD_AUTO           = 0;
    const API_PAYMENT_METHOD_CHECK          = 1;
    const API_PAYMENT_METHOD_DEBIT          = 2;
    const API_PAYMENT_METHOD_CREDIT         = 3;
    const API_PAYMENT_METHOD_DIGITAL_WALLET = 122;
    const API_PAYMENT_METHOD_PENDING_TRANSACTION = 131;

    const API_PAYMENT_GROUP_BANK_CHECK   = '00';
    const API_PAYMENT_GROUP_DEBIT_CARD   = '01';
    const API_PAYMENT_GROUP_CREDIT_CARD  = '02';
    const API_PAYMENT_GROUP_VOUCHER_CARD = '03';
    const API_PAYMENT_GROUP_LOYALTY_CARD = '05';
    const API_PAYMENT_GROUP_CASH_MONEY   = '98';
    const API_PAYMENT_GROUP_OTHER_CARD   = '99';

    const API_PAYMENT_SUBGROUP_CASH_DOWN          = '00';
    const API_PAYMENT_SUBGROUP_PRE_DATED          = '01';
    const API_PAYMENT_SUBGROUP_INSTALLMENT_STORE  = '02';
    const API_PAYMENT_SUBGROUP_INSTALLMENT_ISSUER = '03';
    const API_PAYMENT_SUBGROUP_CASH_DOWN_INTEREST = '04';
    const API_PAYMENT_SUBGROUP_STORE_CREDIT       = '05';
    const API_PAYMENT_SUBGROUP_OTHER_TYPE         = '99';

    const API_RECEIPT_TYPE_PURCHASE               = '00';
    const API_RECEIPT_TYPE_VOUCHER                = '01';
    const API_RECEIPT_TYPE_CHECK                  = '02';
    const API_RECEIPT_TYPE_PAYMENT                = '03';
    const API_RECEIPT_TYPE_MANAGERIAL             = '04';
    const API_RECEIPT_TYPE_CB                     = '05';
    const API_RECEIPT_TYPE_MOBILE_RECHARGE        = '06';
    const API_RECEIPT_TYPE_BONUS_RECHARGE         = '07';
    const API_RECEIPT_TYPE_GIFT_RECHARGE          = '08';
    const API_RECEIPT_TYPE_SP_TRANS_RECHARGE      = '09';
    const API_RECEIPT_TYPE_MEDICATIONS            = '10';

    const API_CARD_READ_TYPE_MAGNETIC                        = 0;
    const API_CARD_READ_TYPE_VISA_CASH_TIBC_V1               = 1;
    const API_CARD_READ_TYPE_VISA_CASH_TIBC_V3               = 2;
    const API_CARD_READ_TYPE_EMV_CONTACT                     = 3;
    const API_CARD_READ_TYPE_EASY_ENTRY_TIBC_V1              = 4;
    const API_CARD_READ_TYPE_CONTACTLESS_MAGNETIC_EMULATION  = 5;
    const API_CARD_READ_TYPE_EMV_CONTACTLESS                 = 6;
    const API_CARD_READ_TYPE_MANUAL                          = 9;

    const API_CARD_READ_STATUS_SUCCESS                    = 0;
    const API_CARD_READ_STATUS_FALLBACK_ERROR             = 1;
    const API_CARD_READ_STATUS_APPLICATION_NOT_SUPPORTED  = 2;

    const PINPAD_TRANSACTION_TABLE = 'gamuza_sitef_pinpad_transaction';

    const ORDER_ATTRIBUTE_IS_SITEF = 'is_sitef';

    const PAYMENT_ATTRIBUTE_SITEF_TRANS_ID = 'sitef_trans_id';

    const XML_PATH_PAYMENT_GAMUZA_SITEF_PINPAD_PAID_STATUS = 'payment/gamuza_sitef_pinpad/paid_status';

    const LOG = 'gamuza_sitef.log';
}

