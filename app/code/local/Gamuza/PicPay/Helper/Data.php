<?php
/**
 * @package     Gamuza_PicPay
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_PicPay_Helper_Data extends Mage_Core_Helper_Abstract
{
    const API_PAYMENTS_URL               = 'https://appws.picpay.com/ecommerce/public/payments';
    const API_PAYMENTS_STATUS_URL        = 'https://appws.picpay.com/ecommerce/public/payments/{referenceId}/status';
    const API_PAYMENTS_CANCELLATIONS_URL = 'https://appws.picpay.com/ecommerce/public/payments/{referenceId}/cancellations';

    const API_ECOMMERCE_CHECKOUT_QRCODE  = 'https://appws.picpay.com/ecommerce/checkout/qr-code?order={$orderId}&url={paymentUrl}';

    const API_PAYMENT_STATUS_CREATED     = 'created';
    const API_PAYMENT_STATUS_EXPIRED     = 'expired';
    const API_PAYMENT_STATUS_ANALYSIS    = 'analysis';
    const API_PAYMENT_STATUS_PAID        = 'paid';
    const API_PAYMENT_STATUS_COMPLETED   = 'completed';
    const API_PAYMENT_STATUS_REFUNDED    = 'refunded';
    const API_PAYMENT_STATUS_CHARGEDBACK = 'chagedback';
    const API_PAYMENT_STATUS_ERROR       = 'error';

    const TRANSACTION_TABLE = 'gamuza_picpay_transaction';

    const ORDER_ATTRIBUTE_IS_PICPAY = 'is_picpay';

    const PAYMENT_ATTRIBUTE_EXT_PAYMENT_ID  = 'ext_payment_id';
    const PAYMENT_ATTRIBUTE_PICPAY_STATUS   = 'picpay_status';
    const PAYMENT_ATTRIBUTE_PICPAY_URL      = 'picpay_url';

    const LOG = 'gamuza_picpay.log';

    public function api ($url, $post = null, $request = null, $store = null)
    {
        $xPicPayToken = $this->getStoreConfig ('x_picpay_token', $store);

        $curl = curl_init ();

        curl_setopt ($curl, CURLOPT_URL, $url);
        curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt ($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt ($curl, CURLOPT_HTTPHEADER, array (
            'Content-Type: application/json',
            'x-picpay-token: ' . $xPicPayToken,
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
        $response = json_decode ($result);

        curl_close ($curl);

        $message = null;

        if (($httpCode = $info ['http_code']) != 200)
        {
            if ($response && is_object ($response)
                && property_exists ($response, 'message')
                && property_exists ($response, 'errors'))
            {
                $responseErrors = null;

                foreach ($response->errors as $error)
                {
                    $responseErrors .= sprintf ('%s: %s', $error->field, $error->message);
                }

                $message = sprintf ('%s [ %s ] %s', $response->message, $responseErrors, $httpCode);
            }
        }

        if (!empty ($message))
        {
            $message = implode (' : ' , array ($request, $url, json_encode ($post), $message, $result));

            throw Mage::exception ('Gamuza_PicPay', $message, $httpCode);
        }

        return $response;
    }

    public function getStoreConfig ($key, $store = null)
    {
        return Mage::getStoreConfig ("payment/gamuza_picpay_payment/{$key}", $store);
    }

    public function getOrderReferenceId ($order)
    {
        /*
        $result = Mage::helper ('core')->formatDate(
            $order->getCreatedAt (),
            Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM,
            true
        );
        */

        return sprintf ('%s-%s', $order->getIncrementId (),
            /*
            str_replace (array ('/', ':', ' '), '-', $result))
            */
            $order->getProtectCode())
        ;
    }
}

