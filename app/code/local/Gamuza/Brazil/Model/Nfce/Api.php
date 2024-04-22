<?php
/**
 * @package     Gamuza_Brazil
 * @copyright   Copyright (c) 2024 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * NFC-e API
 */
class Gamuza_Brazil_Model_Nfce_Api extends Mage_Api_Model_Resource_Abstract
{
    /**
     * Attributes map array per entity type
     *
     * @var array
     */
    protected $_attributesMap = array(
        'nfce' => array ()
    );

    public function __construct ()
    {
        // parent::__construct ();

        $this->_shippingCountryId = Mage::getStoreConfig (Mage_Shipping_Model_Config::XML_PATH_ORIGIN_COUNTRY_ID);
        $this->_shippingRegionId  = Mage::getStoreConfig (Mage_Shipping_Model_Config::XML_PATH_ORIGIN_REGION_ID);
    }

    public function items ($filters = array ())
    {
        $collection = Mage::getModel ('brazil/nfce')->getCollection ()
            ->addOrderInfo ()
        ;

        /** @var $apiHelper Mage_Api_Helper_Data */
        $apiHelper = Mage::helper ('api');

        $filters = $apiHelper->parseFilters ($filters, $this->_attributesMap ['nfce']);

        try
        {
            foreach ($filters as $field => $value)
            {
                $collection->addFieldToFilter ($field, $value);
            }
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_fault ('filters_invalid', $e->getMessage ());
        }

        $result = array ();

        foreach ($collection as $nfce)
        {
            $data = array (
                'entity_id'       => intval ($nfce->getId ()),
                'order_id'        => intval ($nfce->getOrderId ()),
                'customer_id'     => intval ($nfce->getCustomerId ()),
                'increment_id'    => strval ($nfce->getIncrementId ()),
                'protect_code'    => strval ($nfce->getProtectCode ()),
                'environment_id'  => intval ($nfce->getEnvironmentId ()),
                'version_id'      => strval ($nfce->getVersionId ()),
                'model_id'        => intval ($nfce->getModelId ()),
                'series_id'       => intval ($nfce->getSeriesId ()),
                'batch_id'        => intval ($nfce->getBatchId ()),
                'country_id'      => intval ($nfce->getCountryId ()),
                'region_id'       => intval ($nfce->getRegionId ()),
                'city_id'         => intval ($nfce->getCityId ()),
                'number_id'       => intval ($nfce->getNumberId ()),
                'operation_id'    => intval ($nfce->getOperationId ()),
                'destiny_id'      => intval ($nfce->getDestinyId ()),
                'print_id'        => intval ($nfce->getPrintId ()),
                'emission_id'     => intval ($nfce->getEmissionId ()),
                'finality_id'     => intval ($nfce->getFinalityId ()),
                'consumer_id'     => intval ($nfce->getConsumerId ()),
                'presence_id'     => intval ($nfce->getPresenceId ()),
                'intermediary_id' => intval ($nfce->getIntermediaryId ()),
                'process_id'      => intval ($nfce->getProcessId ()),
                'freight_id'      => intval ($nfce->getFreightId ()),
                'crt_id'          => intval ($nfce->getCrtId ()),
                'operation_name'  => strval ($nfce->getOperationName ()),
                'code'            => strval ($nfce->getCode ()),
                'key'             => $nfce->getKey (),
                'digit'           => $nfce->getDigit (),
                'customer_taxvat' => strval ($nfce->getCustomerTaxvat ()),
                'customer_email'  => strval ($nfce->getCustomerEmail ()),
                'customer_firstname' => strval ($nfce->getCustomerFirstname ()),
                'customer_lastname'  => strval ($nfce->getCustomerLastname ()),
                'payment_method'  => strval ($nfce->getPaymentMethod ()),
                'payment_amount'  => floatval ($nfce->getPaymentAmount ()),
                'created_at'      => strval ($nfce->getCreatedAt ()),
                'updated_at'      => $nfce->getUpdatedAt (),
                'emission_at'     => $nfce->getEmissionAt (),
                'response_at'     => $nfce->getResponseAt (),
                'response_application' => $nfce->getResponseApplication (),
                'response_reason' => $nfce->getResponseReason (),
                'response_key'    => $nfce->getResponseKey (),
                'response_id'     => $nfce->getResponseId (),
                'receipt_id'      => $nfce->getReceiptId (),
                'average_id'      => intval ($nfce->getAverageId ()),
                // Toluca_PDV
                'is_pdv'          => boolval ($nfce->getIsPdv ()),
                'pdv_cashier_id'  => intval ($nfce->getPdvCashierId ()),
                'pdv_operator_id' => intval ($nfce->getPdvOperatorId ()),
                'pdv_customer_id' => intval ($nfce->getPdvCustomerId ()),
                'pdv_history_id'  => intval ($nfce->getPdvHistoryId ()),
                'pdv_sequence_id' => intval ($nfce->getPdvSequenceId ()),
                'pdv_table_id'    => intval ($nfce->getPdvTableId ()),
            );

            $orderItemCollection = Mage::getModel ('sales/order_item')->getCollection ()
                ->setOrderFilter ($nfce->getOrderId ())
            ;

            foreach ($orderItemCollection as $item)
            {
                $data ['items'][] = array(
                    'item_id'         => intval ($item->getId ()),
                    'product_id'      => intval ($item->getProductId ()),
                    'product_type'    => strval ($item->getProductType ()),
                    'product_options' => $item->getProductOptions (),
                    'weight'          => floatval ($item->getWeight ()),
                    'is_virtual'      => boolval ($item->getIsVirtual ()),
                    'sku'             => strval ($item->getSku ()),
                    'name'            => strval ($item->getName ()),
                    'free_shipping'   => boolval ($item->getFreeShipping ()),
                    'qty_canceled'    => floatval ($item->getQtyCanceled ()),
                    'qty_invoiced'    => floatval ($item->getQtyInvoiced ()),
                    'qty_ordered'     => floatval ($item->getQtyOrdered ()),
                    'qty_refunded'    => floatval ($item->getQtyRefunded ()),
                    'qty_shipped'     => floatval ($item->getQtyShipped ()),
                    'row_weight'      => floatval ($item->getRowWeight ()),
                    'base_price'      => floatval ($item->getBasePrice ()),
                    'base_original_price'    => floatval ($item->getBaseOriginalPrice ()),
                    'base_discount_amount'   => floatval ($item->getBaseDiscountAmount ()),
                    'base_discount_invoiced' => floatval ($item->getBaseDiscountInvoiced ()),
                    'base_amount_refunded'   => floatval ($item->getBaseAmountRefunded ()),
                    'base_row_total'         => floatval ($item->getBaseRowTotal ()),
                    'base_row_invoiced'      => floatval ($item->getBaseRowInvoiced ()),
                    'gift_message_available' => intval ($item->getGiftMessageAvailable ()),
                    // DFe
                    'brazil_ncm'  => $item->getBrazilNcm (),
                    'brazil_cest' => $item->getBrazilCest (),
                    'brazil_cfop' => $item->getBrazilCfop (),
                );
            }

            $orderAddressCollection = Mage::getModel ('sales/order_address')->getCollection ()
                ->setOrderFilter ($nfce->getOrderId ())
            ;

            foreach ($orderAddressCollection as $address)
            {
                $data ['addresses'][] = array(
                    'address_type' => strval ($address->getAddressType ()),
                    'entity_id'    => intval ($address->getId ()),
                    'parent_id'    => intval ($address->getParentId ()),
                    'region_id'    => intval ($address->getRegionId ()),
                    'postcode'     => preg_replace ('[\D]', '', $address->getPostcode ()),
                    'street'       => $address->getStreet (),
                    'city'         => strval ($address->getCity ()),
                    'region'       => strval ($address->getRegion ()),
                    'region_code'  => strval ($address->getRegionCode ()),
                    'country_id'   => strval ($address->getCountryId ()),
                    'country_name' => strval ($address->getCountryModel ()->getName ()),
                    'country_iso2' => strval ($address->getCountryModel ()->getIso2Code ()),
                    'country_iso3' => strval ($address->getCountryModel ()->getIso3Code ()),
                    'firstname' => strval ($address->getFirstname ()),
                    'lastname'  => strval ($address->getLastname ()),
                    'email'     => strval ($address->getEmail ()),
                    'cellphone' => preg_replace ('[\D]', '', $address->getCellphone ()),
                    'telephone' => $address->getTelephone (),
                    'fax'       => $address->getFax (),
                );
            }

            $orderPaymentCollection = Mage::getModel ('sales/order_payment')->getCollection ()
                ->setOrderFilter ($nfce->getOrderId ())
            ;

            foreach ($orderPaymentCollection as $payment)
            {
                $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_OTHER;

                switch ($payment->getMethod ())
                {
                    case 'banktransfer':           { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_BANK_TRANSFER; break; }
                    case 'cashondelivery':         { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_MONEY;         break; }
                    case 'checkmo':                { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_CHECK;         break; }
                    case 'free':                   { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_NONE;          break; }
                    case 'gamuza_brazil_pix':      { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_PIX;           break; }
                    case 'gamuza_openpix_payment': { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_PIX;           break; }
                    case 'machineondelivery':      { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_CREDIT_CARD;   break; }
                    case 'pagseguropro_boleto':    { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_BANK_SLIP;     break; }
                    case 'pagseguropro_tef':       { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_BANK_TRANSFER; break; }
                    case 'rm_pagseguro_cc':        { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_CREDIT_CARD;   break; }
                }

                $paymentMethod = $payment->getMethod ();

                $data ['payments'][] = array(
                    'entity_id' => intval ($payment->getId ()),
                    'parent_id' => intval ($payment->getParentId ()),
                    'method' => strval ($paymentMethod),
                    'title'  => Mage::getStoreConfig ("payment/{$paymentMethod}/title"),
                    'base_amount_ordered'  => floatval ($payment->getBaseAmountOrdered ()),
                    'base_amount_canceled' => floatval ($payment->getBaseAmountCanceled ()),
                    'base_amount_paid'     => floatval ($payment->getBaseAmountPaid ()),
                    'base_amount_refunded' => floatval ($payment->getBaseAmountRefunded ()),
                    'base_shipping_amount'   => floatval ($payment->getBaseShippingAmount ()),
                    'base_shipping_captured' => floatval ($payment->getBaseShippingCaptured ()),
                    'base_shipping_refunded' => floatval ($payment->getBaseShippingRefunded ()),
                    'additional_information' => $payment->getAdditionalInformation (),
                    // DFe
                    'payment_id' => $paymentId,
                );
            }

            $result [] = $data;
        }

        return $result;
    }

    public function create ($orderIncrementId, $orderProtectCode, $data = array ())
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        if (empty ($data))
        {
            $this->_fault ('data_not_specified');
        }

        $order = $this->_initOrder ($orderIncrementId, $orderProtectCode);

        $nfce = Mage::getModel ('brazil/nfce')->load ($order->getId (), 'order_id');

        if ($nfce && $nfce->getId ())
        {
            goto __return;
        }

        $destinyId = 0;

        $orderBillingAddress = $order->getBillingAddress ();

        if (!strcmp ($orderBillingAddress->getCountryId (), $this->_shippingCountryId)
            && !strcmp ($orderBillingAddress->getRegionId (), $this->_shippingRegionId))
        {
            $destinyId = 1;
        }
        else if (!strcmp ($orderBillingAddress->getCountryId (), $this->_shippingCountryId)
            && strcmp ($orderBillingAddress->getRegionId (), $this->_shippingRegionId) != 0)
        {
            $destinyId = 2;
        }
        else if (strcmp ($orderBillingAddress->getCountryId (), $this->_shippingCountryId) != 0
            && strcmp ($orderBillingAddress->getRegionId (), $this->_shippingRegionId) != 0)
        {
            $destinyId = 3;
        }

        $polynomial = hash ('crc32b', $order->getId ());

        $nfce = Mage::getModel ('brazil/nfce')
            ->addData ($data)
            ->setOrderId ($order->getId ())
            ->setDestinyId ($destinyId)
            ->setNumberId (Mage::helper ('brazil')->getNumberId ('nfce', array ('order_id' => $order->getId ())))
            ->setCode (hexdec ($polynomial))
            ->setCreatedAt (date ('c'))
        ;

        $paymentAmount = $order->getPayment ()->getBaseAmountOrdered ();
        $paymentMethod = null;

        switch ($order->getPayment ()->getMethod ())
        {
            case 'cashondelivery': { $paymentMethod = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_MONEY; break; }
        }

        $nfce->setPaymentAmount ($paymentAmount)
            ->setPaymentMethod ($paymentMethod)
            ->save ()
        ;

    __return:

        $result = array ();

        $items = $this->items (array(
            'main_table.entity_id' => $nfce->getId (),
        ));

        if (count ($items) > 0)
        {
            $result = reset ($items);
        }

        return $result;
    }

    /**
     * Initialize order model
     *
     * @param mixed $orderIncrementId
     * @param mixed $orderProtectCode
     * @return Mage_Sales_Model_Order
     */
    protected function _initOrder ($orderIncrementId, $orderProtectCode)
    {
        $order = Mage::getModel ('sales/order')->getCollection ()
            ->addFieldToFilter ('increment_id', array ('eq' => $orderIncrementId))
            ->addFieldToFilter ('protect_code', array ('eq' => $orderProtectCode))
            ->getFirstItem ()
        ;

        if (!$order || !$order->getId ())
        {
            $this->_fault ('order_not_exists');
        }

        return $order;
    }
}

