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

    protected $_createAttributeList = array(
        'environment_id',
        'country_id',
        'region_id',
        'city_id',
        'operation_id',
        'operation_name',
        'emission_id',
        'finality_id',
        'consumer_id',
        'presence_id',
        'intermediary_id',
        'process_id',
        'freight_id',
        'crt_id',
        'print_id',
        'model_id',
        'series_id',
        'batch_id',
        'version',
        'customer_taxvat',
        'customer_rg_ie',
        'customer_ie_icms',
        'observation',
    );

    protected $_authorizeAttributeList = array(
        'environment_id',
        'qr_code',
        'url_key',
        'emitted_at',
        'average_id',
        'process_id',
        'receipt_id',
        'protocol_id',
        'received_id',
        'received_at',
        'application',
        'reason',
        'key',
    );

    protected $_correctAttributeList = array(
        'environment_id',
        'emitted_at',
        'process_id',
        'protocol_id',
        'received_id',
        'received_at',
        'application',
        'reason',
        'key',
        'name',
        'description',
        'justification',
        'event_id',
        'organ_id',
        'type_id',
        'sequence_id',
        'version',
    );

    protected $_cancelAttributeList = array(
        'environment_id',
        'emitted_at',
        'process_id',
        'protocol_id',
        'received_id',
        'received_at',
        'application',
        'reason',
        'key',
        'name',
        'description',
        'justification',
        'event_id',
        'organ_id',
        'type_id',
        'sequence_id',
        'version',
    );

    public function __construct ()
    {
        // parent::__construct ();

        $this->_shippingCountryId = Mage::getStoreConfig (Mage_Shipping_Model_Config::XML_PATH_ORIGIN_COUNTRY_ID);
        $this->_shippingRegionId  = Mage::getStoreConfig (Mage_Shipping_Model_Config::XML_PATH_ORIGIN_REGION_ID);

        $this->_brazilIBPTImport = Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_IBPT_IMPORT);
    }

    public function items ($filters = array ())
    {
        $collection = Mage::getModel ('brazil/nfce')->getCollection ()
            ->addOrderInfo ()
            ->addIBGEInfo ()
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
                'environment_id'  => intval ($nfce->getEnvironmentId ()),
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
                'version'         => strval ($nfce->getVersion ()),
                'code'            => strval ($nfce->getCode ()),
                'key'             => $nfce->getKey (),
                'digit'           => $nfce->getDigit (),
                'customer_rg_ie'  => $nfce->getCustomerRgIe (),
                'customer_ie_icms' => intval ($nfce->getCustomerIeIcms ()),
                'observation'     => $nfce->getObservation (),
                'fisco'           => $nfce->getFisco (),
                'created_at'      => strval ($nfce->getCreatedAt ()),
                'updated_at'      => $nfce->getUpdatedAt (),
                'signed_at'       => $nfce->getSignedAt (),
                'status_id'       => strval ($nfce->getStatusId ()),
                'correct_id'      => intval ($nfce->getCorrectId ()),
                'cancel_id'       => intval ($nfce->getCancelId ()),
                // Sales_Order
                'increment_id'    => strval ($nfce->getIncrementId ()),
                'protect_code'    => strval ($nfce->getProtectCode ()),
                'customer_id'     => intval ($nfce->getCustomerId ()),
                'customer_email'  => strval ($nfce->getCustomerEmail ()),
                'customer_firstname' => strval ($nfce->getCustomerFirstname ()),
                'customer_lastname'  => strval ($nfce->getCustomerLastname ()),
                'customer_taxvat' => strval ($nfce->getCustomerTaxvat ()),
                'base_discount_amount' => floatval ($nfce->getBaseDiscountAmount ()),
                'base_shipping_amount' => floatval ($nfce->getBaseShippingAmount ()),
                'base_shipping_discount_amount' => floatval ($nfce->getBaseShippingDiscountAmount ()),
                'payment_authorization_amount'  => floatval ($nfce->getPaymentAuthorizationAmount ()),
                // Toluca_PDV
                'is_pdv'          => boolval ($nfce->getIsPdv ()),
                'pdv_cashier_id'  => intval ($nfce->getPdvCashierId ()),
                'pdv_operator_id' => intval ($nfce->getPdvOperatorId ()),
                'pdv_customer_id' => intval ($nfce->getPdvCustomerId ()),
                'pdv_history_id'  => intval ($nfce->getPdvHistoryId ()),
                'pdv_sequence_id' => intval ($nfce->getPdvSequenceId ()),
                'pdv_table_id'    => intval ($nfce->getPdvTableId ()),
                // IBGE
                'country_name' => strval ($nfce->getCountryName ()),
                'region_code'  => strval ($nfce->getRegionCode ()),
                'region_name'  => strval ($nfce->getRegionName ()),
                'city_name'    => strval ($nfce->getCityName ()),
            );

            $responseCollection = Mage::getModel ('brazil/nfce_response')->getCollection ()
                ->addFieldToFilter ('nfce_id', array ('eq' => $nfce->getId ()))
            ;

            foreach ($responseCollection as $response)
            {
                $data ['responses'][] = array(
                    'entity_id'     => intval ($response->getId ()),
                    'nfce_id'       => intval ($response->getNfceId ()),
                    'environment_id' => intval ($response->getEnvironmentId ()),
                    'process_id'     => intval ($response->getProcessId ()),
                    'received_id'   => intval ($response->getReceivedId ()),
                    'protocol_id'   => intval ($response->getProtocolId ()),
                    'receipt_id'    => intval ($response->getReceiptId ()),
                    'average_id'    => intval ($response->getAverageId ()),
                    'application'   => strval ($response->getApplication ()),
                    'reason'        => strval ($response->getReason ()),
                    'key'           => strval ($response->getKey ()),
                    'qr_code'       => $response->getQrCode (),
                    'url_key'       => $response->getUrlKey (),
                    'created_at'    => $response->getCreatedAt (),
                    'emitted_at'    => $response->getEmittedAt (),
                    'received_at'   => $response->getReceivedAt (),
                );
            }

            $eventCollection = Mage::getModel ('brazil/nfce_event')->getCollection ()
                ->addFieldToFilter ('nfce_id', array ('eq' => $nfce->getId ()))
            ;

            foreach ($eventCollection as $event)
            {
                $data ['events'][] = array(
                    'entity_id'      => intval ($event->getId ()),
                    'nfce_id'        => intval ($event->getNfceId ()),
                    'environment_id' => intval ($event->getEnvironmentId ()),
                    'process_id'     => intval ($event->getProcessId ()),
                    'received_id'    => intval ($event->getReceivedId ()),
                    'protocol_id'    => intval ($event->getProtocolId ()),
                    'type_id'        => intval ($event->getTypeId ()),
                    'organ_id'       => intval ($event->getOrganId ()),
                    'event_id'       => intval ($event->getEventId ()),
                    'sequence_id'    => intval ($event->getSequenceId ()),
                    'application'    => strval ($event->getApplication ()),
                    'reason'         => strval ($event->getReason ()),
                    'key'            => strval ($event->getKey ()),
                    'name'           => $event->getName (),
                    'description'    => strval ($event->getDescription ()),
                    'justification'  => strval ($event->getJustification ()),
                    'created_at'     => $event->getCreatedAt (),
                    'emitted_at'     => $event->getEmittedAt (),
                    'received_at'    => $event->getReceivedAt (),
                );
            }

            $order = $this->_initOrder ($nfce->getIncrementId (), $nfce->getProtectCode ());

            $brazilNCM = array ();

            foreach ($order->getAllVisibleItems () as $item)
            {
                $brazilNCM [] = $item->getBrazilNcm ();
            }

            $brazilIBPTCollection = Mage::getModel ('brazil/ibpt')->getCollection ()
                ->addFieldToFilter ('code', array ('in' => $brazilNCM))
            ;

            foreach ($order->getAllVisibleItems () as $item)
            {
                $orderItem = array(
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
                    // custom
                    'gtin' => $item->getGtin (),
                    // DFe
                    'brazil_ncm'  => $item->getBrazilNcm (),
                    'brazil_cest' => $item->getBrazilCest (),
                    'brazil_cfop' => $item->getBrazilCfop (),
                    'brazil_ibpt' => null,
                );

                foreach ($brazilIBPTCollection as $ibpt)
                {
                    $productBrazilNCM = $item->getBrazilNcm ();

                    if (!strcmp ($ibpt->getCode (), $productBrazilNCM))
                    {
                        $orderItem ['brazil_ibpt'] = array(
                            'filename' => $this->_brazilIBPTImport,
                            'entity_id'   => intval ($ibpt->getId ()),
                            'code'        => strval ($ibpt->getCode ()),
                            'exception'   => $ibpt->getException (),
                            'type'        => intval ($ibpt->getType ()),
                            'description' => strval ($ibpt->getDescription ()),
                            'national_federal' => floatval ($ibpt->getNationalFederal ()),
                            'imported_federal' => floatval ($ibpt->getImportedFederal ()),
                            'state' => floatval ($ibpt->getState ()),
                            'local' => floatval ($ibpt->getLocal ()),
                            'key'     => strval ($ibpt->getKey ()),
                            'version' => strval ($ibpt->getVersion ()),
                            'source'  => strval ($ibpt->getSource ()),
                            'begin_at'   => strval ($ibpt->getBeginAt ()),
                            'end_at'     => strval ($ibpt->getEndAt ()),
                            'created_at' => strval ($ibpt->getCreatedAt ()),
                        );

                        break;
                    }
                }

                $data ['items'][] = $orderItem;
            }

            foreach ($order->getAllAddresses () as $address)
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

            foreach ($order->getAllPayments () as $payment)
            {
                $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_OTHER;

                switch ($payment->getMethod ())
                {
                    case 'banktransfer':           { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_BANK_TRANSFER; break; }
                    case 'cashondelivery':         { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_MONEY;         break; }
                    case 'checkmo':                { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_CHECK;         break; }
                    case 'free':                   { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_NONE;          break; }
                    case 'gamuza_brazil_pix':      { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_STATIC_PIX;    break; }
                    case 'gamuza_openpix_payment': { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_DYNAMIC_PIX;   break; }
                    case 'machineondelivery':      { $paymentId = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_DEBIT_CARD;    break; }
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
                    'additional_information' => $payment->getAdditionalInformation () ?: null,
                    // DFe
                    'payment_id' => $paymentId,
                );
            }

            $result [] = $data;
        }

        return $result;
    }

    public function create ($orderIncrementId, $orderProtectCode, $data, $updateIBPT = false)
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

        $order = $this->_initIBPT ($order, $updateIBPT);

        $nfce = $this->_initNFCe ($order, false);

        if (!$nfce || !$nfce->getId ())
        {
            $nfce = Mage::getModel ('brazil/nfce')
                ->setCreatedAt (date ('c'))
            ;
        }

        if (empty ($nfce->getNumberId ()))
        {
            $numberId = Mage::helper ('brazil')->getIncrementId ('nfce', 'number_id', array ('order_id' => $order->getId ()));

            $nfce->setNumberId ($numberId);
        }

        if (!strcmp ($nfce->getStatusId (), Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED))
        {
            $this->_fault ('nfce_already_authorized');
        }

        if (!strcmp ($nfce->getStatusId (), Gamuza_Brazil_Helper_Data::NFE_STATUS_CANCELED))
        {
            $this->_fault ('nfce_already_canceled');
        }

        foreach ($this->_createAttributeList as $attribute)
        {
            if (array_key_exists ($attribute, $data))
            {
                $nfce->setData ($attribute, $data [$attribute]);
            }
            else
            {
                $customMessage = Mage::helper ('brazil')->__('Requested data not specified.') . PHP_EOL
                    . PHP_EOL . Mage::helper ('brazil')->__('Attribute name: %s', $attribute);

                $this->_fault ('data_not_specified', $customMessage);
            }
        }

        $codeList = array ('danfe', 'label');

        foreach ($codeList as $code)
        {
            $pdfDir = Mage::app ()->getConfig ()->getVarDir ('brazil') . DS . 'nfce' . DS . 'pdf' . DS . $code;

            if (!is_dir ($pdfDir))
            {
                mkdir ($pdfDir, 0777, true);
            }
        }

        $destinyId = 0;

        $orderBillingAddress = $order->getBillingAddress ();

        if (!strcmp ($orderBillingAddress->getCountryId (), $this->_shippingCountryId)
            && !strcmp ($orderBillingAddress->getRegionId (), $this->_shippingRegionId))
        {
            $destinyId = Gamuza_Brazil_Helper_Data::NFE_DESTINY_INTERNAL;
        }
        else if (!strcmp ($orderBillingAddress->getCountryId (), $this->_shippingCountryId)
            && strcmp ($orderBillingAddress->getRegionId (), $this->_shippingRegionId) != 0)
        {
            $destinyId = Gamuza_Brazil_Helper_Data::NFE_DESTINY_INTERSTATE;
        }
        else if (strcmp ($orderBillingAddress->getCountryId (), $this->_shippingCountryId) != 0
            && strcmp ($orderBillingAddress->getRegionId (), $this->_shippingRegionId) != 0)
        {
            $destinyId = Gamuza_Brazil_Helper_Data::NFE_DESTINY_ABROAD;
        }

        $polynomial = hash ('crc32b', $order->getId ());

        $nfce->setStatusId (Gamuza_Brazil_Helper_Data::NFE_STATUS_CREATED)
            ->setOrderId ($order->getId ())
            ->setDestinyId ($destinyId)
            ->setCode (hexdec ($polynomial))
            ->save ()
        ;

        /**
         * CPF or CNPJ
         */
        if (strlen ($nfce->getCustomerTaxvat ()) == 11
            || strlen ($nfce->getCustomerTaxvat ()) == 14)
        {
            $order->setCustomerTaxvat ($nfce->getCustomerTaxvat ())->save ();
        }

        if (!empty ($nfce->getCustomerRgIe ()))
        {
            $order->setBrazilRgIe ($nfce->getCustomerRgIe ())->save ();
        }

        if (!empty ($nfce->getCustomerIeIcms ()))
        {
            $order->setBrazilIeIcms ($nfce->getCustomerIeIcms ())->save ();
        }

        return $this->_getNFCe ($nfce);
    }

    public function sign ($orderIncrementId, $orderProtectCode, $fisco, $key, $digit, $info)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        if (empty ($fisco))
        {
            $this->_fault ('fisco_not_specified');
        }

        if (empty ($key))
        {
            $this->_fault ('key_not_specified');
        }

        if (strlen ($key) != 44)
        {
            $this->_fault ('key_invalid');
        }

        if (!ctype_digit ($digit))
        {
            $this->_fault ('digit_not_specified');
        }

        $info = base64_decode ($info);

        if (!simplexml_load_string ($info))
        {
            $this->_fault ('info_not_specified');
        }

        $order = $this->_initOrder ($orderIncrementId, $orderProtectCode);

        $nfce = $this->_initNFCe ($order);

        if (!strcmp ($nfce->getStatusId (), Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED))
        {
            $this->_fault ('nfce_already_authorized');
        }

        if (!strcmp ($nfce->getStatusId (), Gamuza_Brazil_Helper_Data::NFE_STATUS_CANCELED))
        {
            $this->_fault ('nfce_already_canceled');
        }

        $xmlDir = Mage::app ()->getConfig ()->getVarDir ('brazil') . DS . 'nfce' . DS . 'xml';

        if (!is_dir ($xmlDir))
        {
            mkdir ($xmlDir, 0777, true);
        }

        $xmlFile = sprintf ('%s%s%s-%s-%s.xml', $xmlDir, DS, $order->getIncrementId (), $order->getProtectCode (), $key);

        $result = file_put_contents ($xmlFile, $info);

        if (!is_file ($xmlFile) || $result != strlen ($info) || $result === false)
        {
            $this->_fault ('nfce_not_saved');
        }

        $nfce->setFisco ($fisco)
            ->setKey ($key)
            ->setDigit ($digit)
            ->setStatusId (Gamuza_Brazil_Helper_Data::NFE_STATUS_SIGNED)
            ->setSignedAt (date ('c'))
            ->setUpdatedAt (date ('c'))
            ->save ()
        ;

        return $this->_getNFCe ($nfce);
    }

    public function authorize ($orderIncrementId, $orderProtectCode, $info, $sent, $return, $data)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        if (empty ($info))
        {
            $this->_fault ('info_not_specified');
        }

        if (empty ($sent))
        {
            $this->_fault ('sent_not_specified');
        }

        if (empty ($return))
        {
            $this->_fault ('return_not_specified');
        }

        if (empty ($data))
        {
            $this->_fault ('data_not_specified');
        }

        $order = $this->_initOrder ($orderIncrementId, $orderProtectCode);

        $nfce = $this->_initNFCe ($order);

        if (!strcmp ($nfce->getStatusId (), Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED))
        {
            $this->_fault ('nfce_already_authorized');
        }

        if (!strcmp ($nfce->getStatusId (), Gamuza_Brazil_Helper_Data::NFE_STATUS_CANCELED))
        {
            $this->_fault ('nfce_already_canceled');
        }

        $this->_authorizeValidate ($nfce);

        $response = Mage::getModel ('brazil/nfce_response')
            ->setNfceId ($nfce->getId ())
            ->setCreatedAt (date ('c'))
        ;

        foreach ($this->_authorizeAttributeList as $attribute)
        {
            if (array_key_exists ($attribute, $data))
            {
                $response->setData ($attribute, $data [$attribute]);
            }
            else
            {
                $customMessage = Mage::helper ('brazil')->__('Requested data not specified.') . PHP_EOL
                    . PHP_EOL . Mage::helper ('brazil')->__('Attribute name: %s', $attribute);

                $this->_fault ('data_not_specified', $customMessage);
            }
        }

        $response->save ();

        $codeList = array ('info', 'sent', 'return');

        foreach ($codeList as $code)
        {
            $$code = base64_decode ($$code);

            if (!simplexml_load_string ($$code))
            {
                $this->_fault (sprintf ('%s_not_specified', $code));
            }

            $xmlDir = Mage::app ()->getConfig ()->getVarDir ('brazil') . DS . 'nfce' . DS . 'response' . DS . $code;

            if (!is_dir ($xmlDir))
            {
                mkdir ($xmlDir, 0777, true);
            }

            $xmlFile = sprintf ('%s%s%s-%s-%s.xml', $xmlDir, DS, $order->getIncrementId (), $order->getProtectCode (), $nfce->getKey ());

            $result = file_put_contents ($xmlFile, $$code);

            if (!is_file ($xmlFile) || $result != strlen ($$code) || $result === false)
            {
                $this->_fault ('nfce_not_saved');
            }
        }

        if ($response->getReceivedId () == Gamuza_Brazil_Helper_Data::NFE_RESPONSE_DUPLICATED)
        {
            $nfce->setNumberId (new Zend_Db_Expr ('NULL'));
        }

        $statusId = $response->getReceivedId () == Gamuza_Brazil_Helper_Data::NFE_RESPONSE_AUTHORIZED
            ? Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED
            : Gamuza_Brazil_Helper_Data::NFE_STATUS_DENIED
        ;

        $nfce->setStatusId ($statusId)
            ->setUpdatedAt (date ('c'))
            ->save ()
        ;

        return $this->_getNFCe ($nfce);
    }

    public function correct ($orderIncrementId, $orderProtectCode)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        $order = $this->_initOrder ($orderIncrementId, $orderProtectCode);

        $nfce = $this->_initNFCe ($order);

        if (!strcmp ($nfce->getStatusId (), Gamuza_Brazil_Helper_Data::NFE_STATUS_CANCELED))
        {
            $this->_fault ('nfce_already_canceled');
        }

        if (strcmp ($nfce->getStatusId (), Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED) != 0)
        {
            $this->_fault ('nfce_not_authorized');
        }

        $this->_correctValidate ($nfce);

        if (empty ($nfce->getCorrectId ()))
        {
            $correctId = Mage::helper ('brazil')->getIncrementId ('nfce', 'correct_id', array ('order_id' => $order->getId ()));

            $nfce->setCorrectId ($correctId)
                ->setUpdatedAt (date ('c'))
                ->save ()
            ;
        }

        return $this->_getNFCe ($nfce);
    }

    public function cancel ($orderIncrementId, $orderProtectCode)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        $order = $this->_initOrder ($orderIncrementId, $orderProtectCode);

        $nfce = $this->_initNFCe ($order);

        if (!strcmp ($nfce->getStatusId (), Gamuza_Brazil_Helper_Data::NFE_STATUS_CANCELED))
        {
            $this->_fault ('nfce_already_canceled');
        }

        if (strcmp ($nfce->getStatusId (), Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED) != 0)
        {
            $this->_fault ('nfce_not_authorized');
        }

        $this->_cancelValidate ($nfce);

        if (empty ($nfce->getCancelId ()))
        {
            $cancelId = Mage::helper ('brazil')->getIncrementId ('nfce', 'cancel_id', array ('order_id' => $order->getId ()));

            $nfce->setCancelId ($cancelId)
                ->setUpdatedAt (date ('c'))
                ->save ()
            ;
        }

        return $this->_getNFCe ($nfce);
    }

    public function corrected ($orderIncrementId, $orderProtectCode, $sent, $return, $data)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        if (empty ($sent))
        {
            $this->_fault ('sent_not_specified');
        }

        if (empty ($return))
        {
            $this->_fault ('return_not_specified');
        }

        if (empty ($data))
        {
            $this->_fault ('data_not_specified');
        }

        $order = $this->_initOrder ($orderIncrementId, $orderProtectCode);

        $nfce = $this->_initNFCe ($order);

        if (!strcmp ($nfce->getStatusId (), Gamuza_Brazil_Helper_Data::NFE_STATUS_CANCELED))
        {
            $this->_fault ('nfce_already_canceled');
        }

        if (strcmp ($nfce->getStatusId (), Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED) != 0)
        {
            $this->_fault ('nfce_not_authorized');
        }

        $event = Mage::getModel ('brazil/nfce_event')
            ->setNfceId ($ncfe->getId ())
            ->setCreatedAt (date ('c'))
        ;

        foreach ($this->_correctAttributeList as $attribute)
        {
            if (array_key_exists ($attribute, $data))
            {
                $event->setData ($attribute, $data [$attribute]);
            }
            else
            {
                $customMessage = Mage::helper ('brazil')->__('Requested data not specified.') . PHP_EOL
                    . PHP_EOL . Mage::helper ('brazil')->__('Attribute name: %s', $attribute);

                $this->_fault ('data_not_specified', $customMessage);
            }
        }

        $event->save ();

        $codeList = array ('sent', 'return');

        foreach ($codeList as $code)
        {
            $$code = base64_decode ($$code);

            if (!simplexml_load_string ($$code))
            {
                $this->_fault (sprintf ('%s_not_specified', $code));
            }

            $xmlDir = Mage::app ()->getConfig ()->getVarDir ('brazil') . DS . 'nfce' . DS . 'event' . DS . $code;

            if (!is_dir ($xmlDir))
            {
                mkdir ($xmlDir, 0777, true);
            }

            $xmlFile = sprintf ('%s%s%s-%s-%s.xml', $xmlDir, DS, $order->getIncrementId (), $order->getProtectCode (), $event->getKey ());

            $result = file_put_contents ($xmlFile, $$code);

            if (!is_file ($xmlFile) || $result != strlen ($$code) || $result === false)
            {
                $this->_fault ('nfce_not_saved');
            }
        }

        $nfce->setStatusId (Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED)
            ->setUpdatedAt (date ('c'))
            ->save ()
        ;

        return $this->_getNFCe ($nfce);
    }

    public function canceled ($orderIncrementId, $orderProtectCode, $sent, $return, $data)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        if (empty ($sent))
        {
            $this->_fault ('sent_not_specified');
        }

        if (empty ($return))
        {
            $this->_fault ('return_not_specified');
        }

        if (empty ($data))
        {
            $this->_fault ('data_not_specified');
        }

        $order = $this->_initOrder ($orderIncrementId, $orderProtectCode);

        $nfce = $this->_initNFCe ($order);

        if (!strcmp ($nfce->getStatusId (), Gamuza_Brazil_Helper_Data::NFE_STATUS_CANCELED))
        {
            $this->_fault ('nfce_already_canceled');
        }

        if (strcmp ($nfce->getStatusId (), Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED) != 0)
        {
            $this->_fault ('nfce_not_authorized');
        }

        $event = Mage::getModel ('brazil/nfce_event')
            ->setNfceId ($nfce->getId ())
            ->setCreatedAt (date ('c'))
        ;

        foreach ($this->_cancelAttributeList as $attribute)
        {
            if (array_key_exists ($attribute, $data))
            {
                $event->setData ($attribute, $data [$attribute]);
            }
            else
            {
                $customMessage = Mage::helper ('brazil')->__('Requested data not specified.') . PHP_EOL
                    . PHP_EOL . Mage::helper ('brazil')->__('Attribute name: %s', $attribute);

                $this->_fault ('data_not_specified', $customMessage);
            }
        }

        $event->save ();

        $codeList = array ('sent', 'return');

        foreach ($codeList as $code)
        {
            $$code = base64_decode ($$code);

            if (!simplexml_load_string ($$code))
            {
                $this->_fault (sprintf ('%s_not_specified', $code));
            }

            $xmlDir = Mage::app ()->getConfig ()->getVarDir ('brazil') . DS . 'nfce' . DS . 'event' . DS . $code;

            if (!is_dir ($xmlDir))
            {
                mkdir ($xmlDir, 0777, true);
            }

            $xmlFile = sprintf ('%s%s%s-%s-%s.xml', $xmlDir, DS, $order->getIncrementId (), $order->getProtectCode (), $event->getKey ());

            $result = file_put_contents ($xmlFile, $$code);

            if (!is_file ($xmlFile) || $result != strlen ($$code) || $result === false)
            {
                $this->_fault ('nfce_not_saved');
            }
        }

        $statusId = in_array ($event->getReceivedId (), array (Gamuza_Brazil_Helper_Data::NFE_EVENT_CANCELED, Gamuza_Brazil_Helper_Data::NFE_EVENT_HOMOLOGATED))
            ? Gamuza_Brazil_Helper_Data::NFE_STATUS_CANCELED
            : Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED
        ;

        $nfce->setStatusId ($statusId)
            ->setUpdatedAt (date ('c'))
            ->save ()
        ;

        return $this->_getNFCe ($nfce);
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
        $order = Mage::getModel ('basic/sales_order')->getCollection ()
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

    protected function _initNFCe ($order, $fault = true)
    {
        $nfce = Mage::getModel ('brazil/nfce')->load ($order->getId (), 'order_id');

        if ($fault && (!$nfce || !$nfce->getId ()))
        {
            $this->_fault ('nfce_not_exists');
        }

        return $nfce;
    }

    protected function _initIBPT (Mage_Sales_Model_Order $order, $updateIBPT)
    {
        if (!$order || !$order->getId ())
        {
            $this->_fault ('order_not_exists');
        }

        if (Mage::getStoreConfigFlag (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_IBPT_VALIDATE))
        {
            $ibptKey     = Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_IBPT_KEY);
            $ibptSource  = Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_IBPT_SOURCE);
            $ibptVersion = Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_IBPT_VERSION);

            if (empty ($ibptKey) || empty ($ibptSource) || empty ($ibptVersion))
            {
                $this->_fault ('ibpt_not_imported');
            }

            $beginAt = Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_IBPT_BEGIN_AT);
            $endAt   = Mage::getStoreConfig (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_IBPT_END_AT);

            $now = time ();

            if (strtotime ($beginAt) > $now || strtotime ($endAt) < $now)
            {
                $this->_fault ('ibpt_not_valid');
            }
        }

        $collection = Mage::getModel ('brazil/ibpt')->getCollection ();

        if (!$collection->getSize ())
        {
            $this->_fault ('ibpt_not_imported');
        }

        $fieldList = array(
            Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_NCM,
            Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_CEST,
            Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_BRAZIL_CFOP,
            Gamuza_Brazil_Helper_Data::PRODUCT_ATTRIBUTE_GTIN,
        );

        foreach ($order->getAllVisibleItems () as $item)
        {
            foreach ($fieldList as $field)
            {
                $value = $item->getData ($field);

                if ($updateIBPT)
                {
                    $value = $item->getProduct ()->getData ($field);
                }

                if (empty ($value))
                {
                    $this->_faultOrderItem ($item, $field);
                }

                if ($updateIBPT)
                {
                    $item->setData ($field, $value)->save ();
                }
            }

            $ibpt = Mage::getModel ('brazil/ibpt')->load ($item->getBrazilNcm (), 'code');

            if (!$ibpt || !$ibpt->getId ())
            {
                $this->_faultOrderItem ($item, sprintf ('ncm %s', $item->getBrazilNcm ()));
            }

            $cest = Mage::getModel ('brazil/cest')->load ($item->getBrazilCest (), 'code');

            if (!$cest || !$cest->getId ())
            {
                $this->_faultOrderItem ($item, sprintf ('cest %s', $item->getBrazilCest ()));
            }

            $cfop = Mage::getModel ('brazil/cfop')->load ($item->getBrazilCfop (), 'code');

            if (!$cfop || !$cfop->getId ())
            {
                $this->_faultOrderItem ($item, sprintf ('cfop %s', $item->getBrazilCfop ()));
            }
        }

        return $order;
    }

    protected function _authorizeValidate ($nfce)
    {
        if (!Mage::getStoreConfigFlag (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_AUTHORIZE_VALIDATE))
        {
            return;
        }

        $collection = Mage::getModel ('brazil/nfce')->getCollection ()
            ->addOrderInfo ()
            ->addFieldToFilter ('status_id', array ('nin' => array(
                Gamuza_Brazil_Helper_Data::NFE_STATUS_AUTHORIZED,
                Gamuza_Brazil_Helper_Data::NFE_STATUS_CANCELED,
            )))
            ->addFieldToFilter ('main_table.entity_id', array ('neq' => $nfce->getId ()))
        ;

        if ($collection->getSize () > 0)
        {
            $customMessage = Mage::helper ('brazil')->__('There are NFC-e pending authorization!') . PHP_EOL
                . PHP_EOL . implode (PHP_EOL, $collection->toOptionHash ('number_id', 'increment_id'));

            $this->_fault ('nfce_not_authorized', $customMessage);
        }
    }

    protected function _correctValidate ($nfce)
    {
        if (!Mage::getStoreConfigFlag (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_CORRECT_VALIDATE))
        {
            return;
        }

        $collection = Mage::getModel ('brazil/nfce')->getCollection ()
            ->addOrderInfo ()
            ->addFieldToFilter ('correct_id', array ('gt' => 0))
            ->addFieldToFilter ('status_id', array ('neq' => Gamuza_Brazil_Helper_Data::NFE_STATUS_CANCELED))
            ->addFieldToFilter ('main_table.entity_id', array ('neq' => $nfce->getId ()))
        ;

        if ($collection->getSize () > 0)
        {
            $customMessage = Mage::helper ('brazil')->__('There are NF-e pending correction!') . PHP_EOL
                . PHP_EOL . implode (PHP_EOL, $collection->toOptionHash ('number_id', 'increment_id'));

            $this->_fault ('nfce_not_authorized', $customMessage);
        }
    }

    protected function _cancelValidate ($nfce)
    {
        if (!Mage::getStoreConfigFlag (Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_CANCEL_VALIDATE))
        {
            return;
        }

        $collection = Mage::getModel ('brazil/nfce')->getCollection ()
            ->addOrderInfo ()
            ->addFieldToFilter ('cancel_id', array ('gt' => 0))
            ->addFieldToFilter ('status_id', array ('neq' => Gamuza_Brazil_Helper_Data::NFE_STATUS_CANCELED))
            ->addFieldToFilter ('main_table.entity_id', array ('neq' => $nfce->getId ()))
        ;

        if ($collection->getSize () > 0)
        {
            $customMessage = Mage::helper ('brazil')->__('There are NFC-e pending cancelation!') . PHP_EOL
                . PHP_EOL . implode (PHP_EOL, $collection->toOptionHash ('number_id', 'increment_id'));

            $this->_fault ('nfce_not_canceled', $customMessage);
        }
    }

    protected function _faultOrderItem (Mage_Sales_Model_Order_Item $item, $code)
    {
        $productMessage = sprintf ('%s SKU %s ID %s', $item->getName (), $item->getSku (), $item->getId ());
        $customMessage  = Mage::helper ('brazil')->__('Requested %s not specified for product: %s', strtoupper ($code), $productMessage);

        $this->_fault ('data_not_specified', $customMessage);
    }

    protected function _getNFCe ($nfce)
    {
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
}

