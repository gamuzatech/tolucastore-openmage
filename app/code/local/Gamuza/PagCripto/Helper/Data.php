<?php
/**
 * @package     Gamuza_PagCripto
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PagCripto_Helper_Data extends Mage_Core_Helper_Abstract
{
    const PAYMENT_IMAGE_PREFIX = 'images/gamuza/pagcripto/';

    const API_PAYMENT_CREATE_URL = 'https://api.pagcripto.com.br/v2/gateway/createPayment';
    const API_PAYMENT_CHECK_URL  = 'https://api.pagcripto.com.br/v2/gateway/checkPayment?payment_request={id}';
    const API_PAYMENT_CANCEL_URL = 'https://api.pagcripto.com.br/v2/gateway/cancelPayment';
    const API_PAYMENT_LIST_URL   = 'https://api.pagcripto.com.br/v2/gateway/listPayments';

    const API_PAYMENT_STATUS_WAITING_FOR_PAYMENT = 'waiting_for_payment';
    const API_PAYMENT_STATUS_WAITING_FOR_CONFIRMATION = 'waiting_for_confirmation';
    const API_PAYMENT_STATUS_PAID = 'paid';
    const API_PAYMENT_STATUS_ERROR = 'error';

    const TRANSACTION_TABLE = 'gamuza_pagcripto_transaction';

    const ORDER_ATTRIBUTE_IS_PAGCRIPTO = 'is_pagcripto';

    const PAYMENT_ATTRIBUTE_EXT_PAYMENT_ID = 'ext_payment_id';
    const PAYMENT_ATTRIBUTE_PAGCRIPTO_CURRENCY = 'pagcripto_currency';
    const PAYMENT_ATTRIBUTE_PAGCRIPTO_ADDRESS = 'pagcripto_address';
    const PAYMENT_ATTRIBUTE_PAGCRIPTO_AMOUNT = 'pagcripto_amount';
    const PAYMENT_ATTRIBUTE_PAGCRIPTO_STATUS = 'pagcripto_status';
    const PAYMENT_ATTRIBUTE_PAGCRIPTO_CONFIRMATIONS = 'pagcripto_confirmations';
    const PAYMENT_ATTRIBUTE_PAGCRIPTO_RECEIVED_AMOUNT = 'pagcripto_received_amount';
    const PAYMENT_ATTRIBUTE_PAGCRIPTO_PAYMENT_REQUEST = 'pagcripto_payment_request';

    const XML_GLOBAL_PAYMENT_PAGCRIPTO_TYPES = 'global/payment/pagcripto/types';

    const LOG = 'gamuza_pagcripto.log';

    public function api ($url, $post = null, $request = null, $store = null)
    {
        $token = $this->getStoreConfig ('token', $store);

        $curl = curl_init ();

        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt ($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt ($curl, CURLOPT_HTTPHEADER, array (
            'Accept: application/json',
            'Content-Type: application/json',
            'X-Authentication: ' . $token,
        ));
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 2);

        if ($post != null)
        {
            if (empty ($request)) $request = 'POST';

            curl_setopt ($curl, CURLOPT_POST, 1);
            curl_setopt ($curl, CURLOPT_POSTFIELDS, json_encode ($post));
        }

        if ($request != null)
        {
            curl_setopt ($curl, CURLOPT_CUSTOMREQUEST, $request);
        }

        $result   = curl_exec ($curl);
        $info     = curl_getinfo ($curl);
        $response = json_decode ($result, true);

        curl_close ($curl);

        $message = null;

        if (($httpCode = $info ['http_code']) != 200)
        {
            if ($response && is_string ($response))
            {
                $message = sprintf ('%s [ %s ]', $response, $httpCode);
            }
        }

        if (!empty ($message))
        {
            $message = implode (' : ' , array ($request, $url, json_encode ($post), $message, $result));

            throw Mage::exception ('Gamuza_PagCripto', $message, $httpCode);
        }

        return $response;
    }

    public function getStoreConfig ($key, $store = null)
    {
        return Mage::getStoreConfig ("payment/gamuza_pagcripto_payment/{$key}", $store);
    }
}

