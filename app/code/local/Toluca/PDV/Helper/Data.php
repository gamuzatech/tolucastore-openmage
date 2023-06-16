<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Helper_Data extends Mage_Core_Helper_Abstract
{
    const PDV_API_USER  = 'tolucastorepdv';
    const PDV_API_NAME  = 'Toluca Store PDV';
    const PDV_API_EMAIL = 'pdv@toluca.com.br';

    const CASHIER_TABLE  = 'toluca_pdv_cashier';
    const OPERATOR_TABLE = 'toluca_pdv_operator';
    const HISTORY_TABLE  = 'toluca_pdv_history';
    const LOG_TABLE      = 'toluca_pdv_log';

    const ORDER_ATTRIBUTE_IS_SUPER_MODE = 'is_super_mode';
    const ORDER_ATTRIBUTE_IS_PDV = 'is_pdv';
    const ORDER_ATTRIBUTE_PDV_CASHIER_ID  = 'pdv_cashier_id';
    const ORDER_ATTRIBUTE_PDV_OPERATOR_ID = 'pdv_operator_id';
    const ORDER_ATTRIBUTE_PDV_CUSTOMER_ID = 'pdv_customer_id';
    const ORDER_ATTRIBUTE_PDV_HISTORY_ID  = 'pdv_history_id';
    const ORDER_ATTRIBUTE_PDV_SEQUENCE_ID = 'pdv_sequence_id';

    const CASHIER_STATUS_CLOSED = 0;
    const CASHIER_STATUS_OPENED = 1;

    const LOG_TYPE_OPEN      = 'open';
    const LOG_TYPE_REINFORCE = 'reinforce';
    const LOG_TYPE_BLEED     = 'bleed';
    const LOG_TYPE_MONEY     = 'money';
    const LOG_TYPE_CHANGE    = 'change';
    const LOG_TYPE_ORDER     = 'order';
    const LOG_TYPE_CLOSE     = 'close';

    const XML_PATH_DEFAULT_EMAIL_PREFIX = 'customer/create_account/email_prefix';

    const XML_PATH_PDV_SETTING_DEFAULT_CASHIER  = 'pdv/setting/cashier_id';
    const XML_PATH_PDV_SETTING_DEFAULT_OPERATOR = 'pdv/setting/operator_id';
    const XML_PATH_PDV_SETTING_DEFAULT_CUSTOMER = 'pdv/setting/customer_id';

    const XML_PATH_PDV_CASHIER_INCLUDE_ALL_ORDERS = 'pdv/cashier/include_all_orders';

    const XML_PATH_PDV_PAYMENT_METHOD_CASHONDELIVERY = 'pdv/payment_method/money';

    const XML_PATH_PDV_PAYMENT_METHOD_ALL = 'pdv/payment_method';

    public function getTotals (Mage_Adminhtml_Block_Widget_Grid $grid)
    {
        $fieldsTotals = $grid->_fieldsTotals;

        foreach ($grid->getCollection () as $cashier)
        {
            foreach ($fieldsTotals as $id => $value)
            {
                $fieldsTotals [$id] += floatval ($cashier->getData ($id));
            }
        }

        $object = new Varien_Object ();
        $object->addData ($fieldsTotals);

        return $object;
    }

    public function getCustomerEmail ($customerId)
    {
        $customerPrefix = Mage::getStoreConfig (Toluca_PDV_Helper_Data::XML_PATH_DEFAULT_EMAIL_PREFIX);
        $customerDomain = Mage::getStoreConfig (Mage_Customer_Model_Customer::XML_PATH_DEFAULT_EMAIL_DOMAIN);

        $customerCode = intval ($customerId) > 0 ? hash ('crc32b', $customerId) : $customerId;

        return sprintf ('%s+%s@%s', $customerPrefix, $customerCode, $customerDomain);
    }

    public function isPDV ()
    {
        return strpos ($_SERVER ['HTTP_USER_AGENT'], 'TolucaStorePDV') !== false;
    }
}

