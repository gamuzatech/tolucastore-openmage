<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Order API
 */
class Gamuza_Mobile_Model_Order_Api extends Mage_Sales_Model_Order_Api
{
    use Gamuza_Mobile_Trait_Api_Resource;

    /**
     * Initialize attributes map
     */
    public function __construct()
    {
        $this->_attributesMap = array(
            'order' => array('order_id' => 'entity_id'),
            'order_address' => array('address_id' => 'entity_id'),
            'order_payment' => array('payment_id' => 'entity_id')
        );

        $this->_ignoredAttributeCodes = array(
            'global' => array(
                'attribute_set_id',
                'entity_type_id'
            ),
        );
    }

    protected $_orderAttributes = array(
        'entity_id',
        'state', 'status', 'coupon_code', 'protect_code', 'shipping_description', 'is_virtual',
        'base_discount_amount', 'base_discount_canceled',
        'base_discount_invoiced', 'base_discount_refunded',
        'base_grand_total', 'base_shipping_amount',
        'base_shipping_canceled', 'base_shipping_invoiced',
        'base_shipping_refunded', 'base_subtotal',
        'base_subtotal_canceled', 'base_subtotal_invoiced',
        'base_subtotal_refunded', 'base_total_canceled',
        'base_total_invoiced', 'base_total_invoiced_cost',
        'base_total_offline_refunded', 'base_total_online_refunded',
        'base_total_paid', 'base_total_qty_ordered', 'base_total_refunded',
        'discount_amount', 'discount_canceled', 'discount_invoiced', 'discount_refunded',
        'grand_total', 'shipping_amount', 'shipping_canceled', 'shipping_invoiced', 'shipping_refunded',
        'subtotal', 'subtotal_canceled', 'subtotal_invoiced', 'subtotal_refunded',
        'total_canceled', 'total_invoiced', 'total_offline_refunded',
        'total_online_refunded', 'total_paid', 'total_qty_ordered', 'total_refunded',
        'customer_is_guest', 'customer_note_notify', 'email_sent',
        'payment_auth_expiration', 'payment_authorization_expiration',
        'adjustment_negative', 'adjustment_positive',
        'base_adjustment_negative', 'base_adjustment_positive',
        'base_shipping_discount_amount', 'base_total_due',
        'payment_authorization_amount', 'shipping_discount_amount', 'total_due', 'weight',
        'customer_dob', 'increment_id', 'applied_rule_ids', 'base_currency_code',
        'customer_email', 'customer_firstname', 'customer_lastname', 'customer_taxvat',
        'discount_description', 'global_currency_code', 'order_currency_code',
        'remote_ip', 'shipping_method', 'store_currency_code', 'store_name',
        'customer_note', 'created_at', 'updated_at', 'total_item_count',
        'customer_gender', 'coupon_rule_name', 'gift_message_id',
        'firstname', 'lastname', 'telephone', 'postcode',
        'billing_firstname', 'billing_lastname',
        'shipping_firstname', 'shipping_lastname',
        'billing_name', 'shipping_name',
        'customer_stars', 'increment_number',
        'increment_per_store', 'increment_pad_length', /* 'increment_pad_char', 'increment_prefix' */
        'is_motoboy',
        'base_customer_balance_amount', 'base_customer_balance_invoiced', 'base_customer_balance_refunded',
        'customer_balance_amount', 'customer_balance_invoiced', 'customer_balance_refunded',
        'bs_customer_bal_total_refunded', 'customer_bal_total_refunded',
        'pdv_cashier_id', 'pdv_operator_id', 'pdv_customer_id', 'pdv_history_id', 'pdv_sequence_id'
    );

    protected $_orderAddressAttributes = array(
        'region_id', 'fax', 'region', 'postcode', 'lastname', 'street', 'city', 'cellphone',
        'email', 'telephone', 'country_id', 'firstname', 'address_type', 'company'
    );

    protected $_orderItemAttributes = array(
        'item_id', 'parent_item_id', 'quote_item_id', 'order_id', 'product_id',
        'product_type', 'product_options', 'weight', 'is_virtual', 'sku', 'name', 'description',
        'applied_rule_ids', 'additional_data', 'free_shipping', 'is_qty_decimal',
        'no_discount', 'qty_backordered', 'qty_canceled', 'qty_invoiced',
        'qty_ordered', 'qty_refunded', 'qty_shipped', 'base_cost', 'price',
        'base_price', 'original_price', 'base_original_price',
        'discount_percent', 'discount_amount', 'base_discount_amount',
        'discount_invoiced', 'base_discount_invoiced', 'amount_refunded', 'base_amount_refunded',
        'row_total', 'base_row_total', 'row_invoiced', 'base_row_invoiced', 'row_weight',
        'is_nominal', 'discount_refunded', 'base_discount_refunded',
        'gift_message_id', 'gift_message_available', 'mobile_product_code'
    );

    protected $_orderPaymentAttributes = array(
        'base_shipping_captured', 'shipping_captured', 'amount_refunded',
        'base_amount_paid', 'amount_canceled', 'base_amount_authorized',
        'base_amount_paid_online', 'base_amount_refunded_online', 'base_shipping_amount',
        'shipping_amount', 'amount_paid', 'amount_authorized',
        'base_amount_ordered', 'base_shipping_refunded', 'shipping_refunded',
        'base_amount_refunded', 'amount_ordered', 'base_amount_canceled',
        'additional_data', 'method', 'cc_status_description', 'last_trans_id',
        'cc_owner', 'cc_type', 'additional_information', 'cc_trans_id',
        'cc_approval', 'cc_installments', 'cc_status', 'cc_last4', 'ext_payment_id',
        'blockchain_address', 'blockchain_amount',
        'pagcripto_currency', 'pagcripto_address', 'pagcripto_status', 'pagcripto_payment_request',
        'pagcripto_amount', 'pagcripto_confirmations', 'pagcripto_received_amount',
        'picpay_status', 'picpay_url',
        'openpix_status', 'openpix_url', 'openpix_transaction_id', 'openpix_correlation_id',
    );

    protected $_orderStatusHistoryAttributes = array(
        'is_customer_notified', 'is_visible_on_front', 'comment', 'status', 'entity_name', 'created_at'
    );

    protected $_floatAttributes = array(
        /* list */
        'base_discount_amount', 'base_discount_canceled', 'base_discount_invoiced', 'base_discount_refunded',
        'base_grand_total', 'base_shipping_amount', 'base_shipping_canceled', 'base_shipping_invoiced', 'base_shipping_refunded',
        'base_subtotal', 'base_subtotal_canceled', 'base_subtotal_invoiced', 'base_subtotal_refunded',
        'base_total_canceled', 'base_total_invoiced', 'base_total_invoiced_cost', 'base_total_offline_refunded',
        'base_total_online_refunded', 'base_total_paid', 'base_total_qty_ordered', 'base_total_refunded',
        'discount_amount', 'discount_canceled', 'discount_invoiced', 'discount_refunded', 'grand_total',
        'shipping_amount', 'shipping_canceled', 'shipping_invoiced', 'shipping_refunded',
        'subtotal', 'subtotal_canceled', 'subtotal_invoiced', 'subtotal_refunded',
        'total_canceled', 'total_invoiced', 'total_offline_refunded', 'total_online_refunded',
        'total_paid', 'total_qty_ordered', 'total_refunded', 'adjustment_negative', 'adjustment_positive',
        'base_adjustment_negative', 'base_adjustment_positive', 'base_shipping_discount_amount', 'base_total_due',
        'payment_authorization_amount', 'shipping_discount_amount', 'total_due', 'weight',
        /* info */
        'weight', 'qty_backordered', 'qty_canceled', 'qty_invoiced', 'qty_ordered', 'qty_refunded', 'qty_shipped',
        'price', 'base_price', 'original_price', 'base_original_price', 'discount_percent', 'discount_amount', 'base_discount_amount',
        'discount_invoiced', 'base_discount_invoiced', 'amount_refunded', 'base_amount_refunded',
        'row_total', 'base_row_total', 'row_invoiced', 'base_row_invoiced', 'row_weight', 'discount_refunded', 'base_discount_refunded',
        'base_shipping_captured', 'shipping_captured', 'amount_refunded', 'base_amount_paid', 'amount_canceled',
        'base_amount_authorized', 'base_amount_paid_online', 'base_amount_refunded_online', 'base_shipping_amount',
        'shipping_amount', 'amount_paid', 'amount_authorized', 'base_amount_ordered', 'base_shipping_refunded', 'shipping_refunded',
        'base_amount_refunded', 'amount_ordered', 'base_amount_canceled',
        'base_customer_balance_amount', 'base_customer_balance_invoiced', 'base_customer_balance_refunded',
        'customer_balance_amount', 'customer_balance_invoiced', 'customer_balance_refunded',
        'bs_customer_bal_total_refunded', 'customer_bal_total_refunded',
        'pagcripto_amount', 'pagcripto_received_amount',
    );

    protected $_intAttributes = array(
        'entity_id',
        /* list */
        'total_item_count', 'customer_gender', 'gift_message_id',
        /* info */
        'region_id', 'order_id', 'increment_number', 'cc_status', 'cc_installments',
        'item_id', 'parent_item_id', 'quote_item_id', 'order_id', 'product_id',
        'pdv_cashier_id', 'pdv_operator_id', 'pdv_customer_id', 'pdv_history_id', 'pdv_sequence_id',
        /* eav_type */
        'increment_per_store', 'increment_pad_length', /* 'increment_pad_char', */
        /* eav_store */
        /* 'increment_prefix' */
    );

    protected $_boolAttributes = array(
        /* list */
        'is_virtual', 'customer_is_guest', 'customer_note_notify', 'email_sent', 'is_motoboy',
        /* info */
        'free_shipping', 'is_qty_decimal', 'no_discount', 'is_nominal', 'gift_message_available',
        'is_customer_notified', 'is_visible_on_front'
    );

    protected $_strAttributes = array ('fax', 'postcode', 'telephone', 'cellphone');

    /**
     * Retrieve list of orders. Filtration could be applied
     *
     * @param null|object|array $filters
     * @return array
     */
    public function items($filters = null, $code = null)
    {
        $orders = array();

        //TODO: add full name logic
        $billingAliasName = 'billing_o_a';
        $shippingAliasName = 'shipping_o_a';

        /** @var $orderCollection Mage_Sales_Model_Mysql4_Order_Collection */
        $orderCollection = Mage::getModel("sales/order")->getCollection();

        $billingFirstnameField  = "{$billingAliasName}.firstname";
        $billingLastnameField   = "{$billingAliasName}.lastname";
        $shippingFirstnameField = "{$shippingAliasName}.firstname";
        $shippingLastnameField  = "{$shippingAliasName}.lastname";

        $orderCollection->addAttributeToSelect('*')
            ->addAddressFields()
            ->addExpressionFieldToSelect('billing_firstname', "{{billing_firstname}}",
                array('billing_firstname' => $billingFirstnameField))
            ->addExpressionFieldToSelect('billing_lastname', "{{billing_lastname}}",
                array('billing_lastname' => $billingLastnameField))
            ->addExpressionFieldToSelect('shipping_firstname', "{{shipping_firstname}}",
                array('shipping_firstname' => $shippingFirstnameField))
            ->addExpressionFieldToSelect('shipping_lastname', "{{shipping_lastname}}",
                array('shipping_lastname' => $shippingLastnameField))
            ->addExpressionFieldToSelect('billing_name', "CONCAT({{billing_firstname}}, ' ', {{billing_lastname}})",
                array('billing_firstname' => $billingFirstnameField, 'billing_lastname' => $billingLastnameField))
            ->addExpressionFieldToSelect('shipping_name', 'CONCAT({{shipping_firstname}}, " ", {{shipping_lastname}})',
                array('shipping_firstname' => $shippingFirstnameField, 'shipping_lastname' => $shippingLastnameField))
        ;

        $orderCollection->setOrder ('increment_id', Zend_Db_Select::SQL_DESC);

        /** @var $apiHelper Mage_Api_Helper_Data */
        $apiHelper = Mage::helper('api');

        $filters = $apiHelper->parseFilters($filters, $this->_attributesMap['order']);

        try
        {
            foreach ($filters as $field => $value)
            {
                $orderCollection->addFieldToFilter($field, $value);
            }
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_fault('filters_invalid', $e->getMessage());
        }

        $orderCollection->getSelect ()->join (
            array ('eav_store' => Mage::getSingleton ('core/resource')->getTableName ('eav_entity_store')),
            'main_table.store_id = eav_store.store_id',
            array ('increment_prefix')
        );

        $orderCollection->getSelect ()->join (
            array ('eav_type' => Mage::getSingleton ('core/resource')->getTableName ('eav_entity_type')),
            sprintf ("eav_store.entity_type_id = eav_type.entity_type_id AND eav_type.entity_type_code = '%s'", Mage_Sales_Model_Order::ENTITY),
            array ('increment_per_store', 'increment_pad_length', 'increment_pad_char')
        );

        $orderCollection->getSelect ()->join (
            array ('payment' => Mage::getSingleton ('core/resource')->getTableName ('sales/order_payment')),
            'main_table.entity_id = payment.parent_id',
            array ('payment_method' => 'payment.method')
        );

        foreach ($orderCollection as $order)
        {
            $result = $this->_getAttributes($order, 'order', $this->_orderAttributes);

            $result ['increment_number'] = substr ($order->getIncrementId (), intval ($order->getIncrementPadLength ()) * -1);

            foreach ($this->_floatAttributes as $attribute)
            {
                if (array_key_exists ($attribute, $result))
                {
                    $result [$attribute] = floatval ($result [$attribute]);
                }
            }

            foreach ($this->_intAttributes as $attribute)
            {
                if (array_key_exists ($attribute, $result))
                {
                    $result [$attribute] = intval ($result [$attribute]);
                }
            }

            foreach ($this->_boolAttributes as $attribute)
            {
                if (array_key_exists ($attribute, $result))
                {
                    $result [$attribute] = boolval ($result [$attribute]);
                }
            }

            $result ['applied_rule_ids'] = explode (',', $result ['applied_rule_ids']);

            $result ['payment_method'] = $order->getPaymentMethod ();

            $orders [] = $result;
        }

        return $orders;
    }

    /**
     * Retrieve full order information
     *
     * @param string $orderIncrementId
     * @return array
     */
    public function info($orderIncrementId = null, $orderProtectCode = null, $code = null)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        $order = $this->_initOrder($orderIncrementId, $orderProtectCode);

        if ($order->getGiftMessageId() > 0)
        {
            $order->setGiftMessage(
                Mage::getSingleton('giftmessage/message')->load($order->getGiftMessageId())->getMessage()
            );
        }

        $result = $this->_getAttributes($order, 'order', $this->_orderAttributes);

        foreach ($this->_floatAttributes as $attribute)
        {
            if (array_key_exists ($attribute, $result))
            {
                $result [$attribute] = floatval ($result [$attribute]);
            }
        }

        foreach ($this->_intAttributes as $attribute)
        {
            if (array_key_exists ($attribute, $result))
            {
                $result [$attribute] = intval ($result [$attribute]);
            }
        }

        foreach ($this->_boolAttributes as $attribute)
        {
            if (array_key_exists ($attribute, $result))
            {
                $result [$attribute] = boolval ($result [$attribute]);
            }
        }

        $result ['applied_rule_ids'] = explode (',', $result ['applied_rule_ids']);

        $result['shipping_address'] = $this->_getAttributes($order->getShippingAddress(), 'order_address', $this->_orderAddressAttributes);
        $result['billing_address']  = $this->_getAttributes($order->getBillingAddress(), 'order_address', $this->_orderAddressAttributes);

        $countryPhones = Mage::app ()->getLocale ()->getTranslationList ('territorytophone');

        if ($order->getBillingAddress ())
        {
            $result['billing_address']['region_code'] = $order->getBillingAddress ()->getRegionCode ();
            $result['billing_address']['region_name'] = $order->getBillingAddress ()->getRegion ();
            $result['billing_address']['country_iso2'] = $order->getBillingAddress ()->getCountryModel ()->getIso2Code ();
            $result['billing_address']['country_iso3'] = $order->getBillingAddress ()->getCountryModel ()->getIso3Code ();
            $result['billing_address']['country_name'] = $order->getBillingAddress ()->getCountryModel ()->getName ();
            $result['billing_address']['country'] = $order->getBillingAddress ()->getCountryModel ()->getName ();

            foreach ($countryPhones as $phone => $code)
            {
                if ($code == $order->getBillingAddress ()->getCountryId ())
                {
                    $result['billing_address']['country_id'] = ord ($code);
                    $result['billing_address']['country_code'] = $phone;

                    break;
                }
            }
        }

        if ($order->getShippingAddress ())
        {
            $result['shipping_address']['region_code'] = $order->getShippingAddress ()->getRegionCode ();
            $result['shipping_address']['region_name'] = $order->getShippingAddress ()->getRegion ();
            $result['shipping_address']['country_iso2'] = $order->getShippingAddress ()->getCountryModel ()->getIso2Code ();
            $result['shipping_address']['country_iso3'] = $order->getShippingAddress ()->getCountryModel ()->getIso3Code ();
            $result['shipping_address']['country_name'] = $order->getShippingAddress ()->getCountryModel ()->getName ();
            $result['shipping_address']['country'] = $order->getShippingAddress ()->getCountryModel ()->getName ();

            foreach ($countryPhones as $phone => $code)
            {
                if ($code == $order->getShippingAddress ()->getCountryId ())
                {
                    $result['shipping_address']['country_id'] = ord ($code);
                    $result['shipping_address']['country_code'] = $phone;

                    break;
                }
            }
        }

        foreach (array ('billing', 'shipping') as $addressType)
        {
            $result [$addressType . '_address']['street'] = explode ("\n", $result [$addressType . '_address']['street']);

            foreach ($this->_intAttributes as $attribute)
            {
                if (array_key_exists ($attribute, $result [$addressType . '_address']))
                {
                    $result [$addressType . '_address'][$attribute] = intval ($result [$addressType . '_address'][$attribute]);
                }
            }

            foreach ($this->_strAttributes as $attribute)
            {
                if (array_key_exists ($attribute, $result [$addressType . '_address']))
                {
                    $result [$addressType . '_address'][$attribute] = strval ($result [$addressType . '_address'][$attribute]);
                }
            }
        }

        $result['items'] = array();

        foreach ($order->getAllVisibleItems() as $item)
        {
            if ($item->getGiftMessageId() > 0)
            {
                $item->setGiftMessage(
                    Mage::getSingleton('giftmessage/message')->load($item->getGiftMessageId())->getMessage()
                );
            }

            $orderItem = $this->_getAttributes($item, 'order_item', $this->_orderItemAttributes);

            foreach ($this->_floatAttributes as $attribute)
            {
                if (array_key_exists ($attribute, $orderItem))
                {
                    $orderItem [$attribute] = floatval ($orderItem [$attribute]);
                }
            }

            foreach ($this->_intAttributes as $attribute)
            {
                if (array_key_exists ($attribute, $orderItem))
                {
                    $orderItem [$attribute] = intval ($orderItem [$attribute]);
                }
            }

            foreach ($this->_boolAttributes as $attribute)
            {
                if (array_key_exists ($attribute, $orderItem))
                {
                    $orderItem [$attribute] = boolval ($orderItem [$attribute]);
                }
            }

            $orderItem ['applied_rule_ids'] = explode (',', $orderItem ['applied_rule_ids']);

            $productOptions = unserialize ($orderItem ['product_options']);

            foreach ($productOptions as $id => $values)
            {
                if (in_array ($id, array (
                    'options', 'additional_options', 'bundle_options', 'attributes_info',
                )))
                {
                    $orderItem [$id] = $values;
                }
            }

            unset ($orderItem ['product_options']);

            $result['items'][] = $orderItem;
        }

        $result['payment'] = $this->_getAttributes($order->getPayment(), 'order_payment', $this->_orderPaymentAttributes);

        foreach ($this->_floatAttributes as $attribute)
        {
            if (array_key_exists ($attribute, $result ['payment']))
            {
                $result ['payment'][$attribute] = floatval ($result ['payment'][$attribute]);
            }
        }

        foreach ($this->_intAttributes as $attribute)
        {
            if (array_key_exists ($attribute, $result ['payment']))
            {
                $result ['payment'][$attribute] = intval ($result ['payment'][$attribute]);
            }
        }

        $result['status_history'] = array();

        foreach ($order->getAllStatusHistory() as $history)
        {
            $status = $this->_getAttributes($history, 'order_status_history', $this->_orderStatusHistoryAttributes);

            foreach ($this->_boolAttributes as $attribute)
            {
                if (array_key_exists ($attribute, $status))
                {
                    $status [$attribute] = boolval ($status [$attribute]);
                }
            }

            $result['status_history'][] = $status;
        }

        $result ['can_prepare'] = boolval ($order->canPrepare ());
        $result ['can_invoice'] = boolval ($order->canInvoice ());
        $result ['can_ship']    = boolval ($order->canShip ());
        $result ['can_deliver'] = boolval ($order->canDeliver ());
        $result ['can_creditmemo'] = boolval ($order->canCreditmemo ());
        $result ['can_cancel']  = boolval ($order->canCancel ());

        $result ['has_invoices']    = boolval ($order->hasInvoices ());
        $result ['has_shipments']   = boolval ($order->hasShipments ());
        $result ['has_creditmemos'] = boolval ($order->hasCreditmemos ());
        $result ['has_services']    = boolval ($order->hasServices ());

        return $result;
    }

    /**
     * Rate order information
     *
     * @param  string $orderIncrementId
     * @param  int    $stars
     * @param  string $comment
     * @return boolean
     */
    public function rate ($orderIncrementId = null, $orderProtectCode = null, $stars = null, $comment = null, $code = null)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        if (empty ($stars))
        {
            $this->_fault ('stars_not_specified');
        }

        if (empty ($comment))
        {
            $this->_fault ('comment_not_specified');
        }

        $order = $this->_initOrder ($orderIncrementId, $orderProtectCode);

        if ($order->getData (Gamuza_Mobile_Helper_Data::ORDER_ATTRIBUTE_CUSTOMER_STARS))
        {
            $this->_fault ('order_has_rated');
        }

        $result = false;

        try
        {
            $order->setData (Gamuza_Mobile_Helper_Data::ORDER_ATTRIBUTE_CUSTOMER_STARS, $stars)->save ();

            $comment = Mage::helper ('mobile')->__("Rating %s star(s): %s", $stars, $comment);

            $order->addStatusHistoryComment ($comment)
                ->setIsVisibleOnFront (true)
                ->setIsCustomerNotified (true)
                ->setIsCustomerRating (true)
                ->save ()
            ;

            $result = true;
        }
        catch (Exception $e)
        {
            // nothing
        }

        return $result;
    }

    /**
     * Reorder order information
     *
     * @param  string $orderIncrementId
     * @param  string $store
     * @return boolean
     */
    public function reorder ($orderIncrementId = null, $orderProtectCode = null, $code = null, $store = null)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        if (empty ($code))
        {
            $this->_fault ('customer_code_not_specified');
        }

        $order = $this->_initOrder ($orderIncrementId, $orderProtectCode);

        try
        {
            Mage::getModel ('mobile/cart_api')->clear ($code);
        }
        catch (Exception $e)
        {
            // nothing
        }

        $quote = $this->_getCustomerQuote ($code, $store, true);

        $result = false;

        try
        {
            foreach ($order->getAllVisibleItems () as $item)
            {
                $request = new Varien_Object ();

                $productOptions = $item->getProductOptions ();

                if (array_key_exists ('info_buyRequest', $productOptions))
                {
                    $buyRequest = $productOptions ['info_buyRequest'];

                    if (array_key_exists ('qty', $buyRequest))
                    {
                        $request->setData ('qty', $buyRequest ['qty']);
                    }

                    if (array_key_exists ('options', $buyRequest))
                    {
                        $request->setData ('options', $buyRequest ['options']);
                    }

                    if (array_key_exists ('additional_options', $buyRequest))
                    {
                        $request->setData ('additional_options', $buyRequest ['additional_options']);
                    }

                    if (array_key_exists ('super_attribute', $buyRequest))
                    {
                        $request->setData ('super_attribute', $buyRequest ['super_attribute']);
                    }

                    if (array_key_exists ('bundle_option', $buyRequest))
                    {
                        $request->setData ('bundle_option', $buyRequest ['bundle_option']);
                    }
                }

                $quote->addProduct ($item->getProduct (), $request);
            }

            $quote->collectTotals ()->save ();

            $result = true;
        }
        catch (Exception $e)
        {
            // nothing
        }

        return $result;
    }

    /**
     * Retrieve full order information
     *
     * @param string $orderIncrementId
     * @return array
     */
    public function draft($orderIncrementId = null, $orderProtectCode = null, $code = null)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        $order = $this->_initOrder($orderIncrementId, $orderProtectCode);

        $result = Mage::app ()
            ->getLayout ()
            ->createBlock ('mobile/adminhtml_order_draft')
            ->setArea (Mage_Core_Model_App_Area::AREA_ADMINHTML)
            ->setOrder ($order)
            ->setTemplate ('gamuza/mobile/order/draft.phtml')
            ->toHtml ();

        return $result;
    }

    /**
     * Initialize basic order model
     *
     * @param mixed $orderIncrementId
     * @return Mage_Sales_Model_Order
     */
    protected function _initOrder($orderIncrementId, $orderProtectCode = null, $code = null)
    {
        $order = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter ('increment_id', array ('eq' => $orderIncrementId))
            ->addFieldToFilter ('protect_code', array ('eq' => $orderProtectCode))
            ->getFirstItem ()
        ;

        if (!$order->getId())
        {
            $this->_fault('not_exists');
        }

        return $order;
    }
}

