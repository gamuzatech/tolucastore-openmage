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
    const XML_PATH_BRAZIL_NFCE_ENVIRONMENT = Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_ENVIRONMENT;
    const XML_PATH_BRAZIL_NFCE_VERSION     = Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_VERSION;

    const XML_PATH_BRAZIL_NFCE_MODEL  = Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_MODEL;
    const XML_PATH_BRAZIL_NFCE_SERIES = Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_SERIES;

    const XML_PATH_BRAZIL_NFCE_BATCH_ID  = Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_BATCH_ID;
    const XML_PATH_BRAZIL_NFCE_REGION_ID = Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_REGION_ID;
    const XML_PATH_BRAZIL_NFCE_CITY_ID   = Gamuza_Brazil_Helper_Data::XML_PATH_BRAZIL_NFCE_CITY_ID;

    const NFE_PRINT_PORTRAIT      = Gamuza_Brazil_Helper_Data::NFE_PRINT_PORTRAIT;
    const NFE_EMISSION_NORMAL     = Gamuza_Brazil_Helper_Data::NFE_EMISSION_NORMAL;
    const NFE_FINALITY_NORMAL     = Gamuza_Brazil_Helper_Data::NFE_FINALITY_NORMAL;
    const NFE_CONSUMER_FINAL      = Gamuza_Brazil_Helper_Data::NFE_CONSUMER_FINAL;
    const NFE_PRESENCE_DELIVERY   = Gamuza_Brazil_Helper_Data::NFE_PRESENCE_DELIVERY;
    const NFE_INTERMEDIARY_OWN    = Gamuza_Brazil_Helper_Data::NFE_INTERMEDIARY_OWN;
    const NFE_PROCESS_PDV         = Gamuza_Brazil_Helper_Data::NFE_PROCESS_PDV;
    const NFE_FREIGHT_EMITTER     = Gamuza_Brazil_Helper_Data::NFE_FREIGHT_EMITTER;
    const NFE_CRT_SIMPLE_NATIONAL = Gamuza_Brazil_Helper_Data::NFE_CRT_SIMPLE_NATIONAL;
    const NFE_CUSTOMER_IE_NONE    = Gamuza_Brazil_Helper_Data::NFE_CUSTOMER_IE_NONE;

    const NFE_PAYMENT_TYPE_MONEY = Gamuza_Brazil_Helper_Data::NFE_PAYMENT_TYPE_MONEY;

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

        $this->_fantasyName   = Mage::getStoreConfig ('general/store_information/name');
        $this->_companyTaxvat = Mage::getStoreConfig ('general/store_information/taxvat');
        $this->_companyName   = Mage::getStoreConfig ('general/store_information/company_name');
        $this->_companyIE     = Mage::getStoreConfig ('general/store_information/company_ie');
    }

    public function items ($filters = null)
    {
        $collection = Mage::getModel ('brazil/nfce')->getCollection ();

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
            $nfce->setFantasyName ($this->_fantasyName)
                ->setCompanyName ($this->_companyName)
            ;

            $result [] = array (
                'entity_id'       => intval ($nfce->getId ()),
                'order_id'        => intval ($nfce->getOrderId ()),
                'store_id'        => intval ($nfce->getStoreId ()),
                'customer_id'     => intval ($nfce->getCustomerId ()),
                'environment_id'  => intval ($nfce->getEnvironmentId ()),
                'version_id'      => strval ($nfce->getVersionId ()),
                'model_id'        => intval ($nfce->getModelId ()),
                'series_id'       => intval ($nfce->getSeriesId ()),
                'batch_id'        => intval ($nfce->getBatchId ()),
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
                'operation'       => strval ($nfce->getOperation ()),
                'code'            => strval ($nfce->getCode ()),
                'key'             => $nfce->getKey (),
                'digit'           => $nfce->getDigit (),
                'fantasy_name'    => strval ($nfce->getFantasyName ()),
                'company_taxvat'  => strval ($nfce->getCompanyTaxvat ()),
                'company_name'    => strval ($nfce->getCompanyName ()),
                'company_ie'      => strval ($nfce->getCompanyIe ()),
                'customer_taxvat' => strval ($nfce->getCustomerTaxvat ()),
                'customer_email'  => strval ($nfce->getCustomerEmail ()),
                'customer_ie'     => strval ($nfce->getCustomerIe ()),
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
        }

        return $result;
    }

    public function create ($orderIncrementId, $orderProtectCode, $operationId, $operationName)
    {
        if (empty ($orderIncrementId))
        {
            $this->_fault ('order_not_specified');
        }

        if (empty ($orderProtectCode))
        {
            $this->_fault ('code_not_specified');
        }

        if (empty ($operationId) || empty ($operationName))
        {
            $this->_fault ('operation_not_specified');
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
            ->setOrderId ($order->getId ())
            ->setStoreId ($order->getStoreId ())
            ->setCustomerId ($order->getCustomerId ())
            ->setEnvironmentId (Mage::getStoreConfig (self::XML_PATH_BRAZIL_NFCE_ENVIRONMENT))
            ->setVersionId (Mage::getStoreConfig (self::XML_PATH_BRAZIL_NFCE_VERSION))
            ->setModelId (Mage::getStoreConfig (self::XML_PATH_BRAZIL_NFCE_MODEL))
            ->setSeriesId (Mage::getStoreConfig (self::XML_PATH_BRAZIL_NFCE_SERIES))
            ->setBatchId (Mage::getStoreConfig (self::XML_PATH_BRAZIL_NFCE_BATCH_ID))
            ->setRegionId (Mage::getStoreConfig (self::XML_PATH_BRAZIL_NFCE_REGION_ID))
            ->setCityId (Mage::getStoreConfig (self::XML_PATH_BRAZIL_NFCE_CITY_ID))
            ->setNumberId (Mage::helper ('brazil')->getNumberId ('nfce'))
            ->setOperationId ($operationId)
            ->setDestinyId ($destinyId)
            ->setPrintId (self::NFE_PRINT_PORTRAIT)
            ->setEmissionId (self::NFE_EMISSION_NORMAL)
            ->setFinalityId (self::NFE_FINALITY_NORMAL)
            ->setConsumerId (self::NFE_CONSUMER_FINAL)
            ->setPresenceId (self::NFE_PRESENCE_DELIVERY)
            ->setIntermediaryId (self::NFE_INTERMEDIARY_OWN)
            ->setProcessId (self::NFE_PROCESS_PDV)
            ->setFreightId (self::NFE_FREIGHT_EMITTER)
            ->setCrtId (self::NFE_CRT_SIMPLE_NATIONAL)
            ->setOperation ($operationName)
            ->setCode (hexdec ($polynomial))
            ->setCompanyTaxvat ($this->_companyTaxvat)
            ->setCompanyIe ($this->_companyIE)
            ->setCustomerTaxvat ($order->getCustomerTaxvat ())
            ->setCustomerEmail ($order->getCustomerEmail ())
            ->setCustomerIe (self::NFE_CUSTOMER_IE_NONE)
            ->setCreatedAt (date ('c'))
        ;

        if (Mage::helper ('core')->isModuleEnabled ('Toluca_PDV')
            && $order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_IS_PDV))
        {
            $nfce->setCustomerId ($order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_CUSTOMER_ID));

            $nfce->setIsPdv ($order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_IS_PDV));

            $nfce->setPdvCashierId  ($order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_CASHIER_ID));
            $nfce->setPdvOperatorId ($order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_OPERATOR_ID));
            $nfce->setPdvCustomerId ($order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_CUSTOMER_ID));
            $nfce->setPdvHistoryId  ($order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_HISTORY_ID));
            $nfce->setPdvSequenceId ($order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_SEQUENCE_ID));
            $nfce->setPdvTableId    ($order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_TABLE_ID));
        }

        $paymentAmount = $order->getPayment ()->getBaseAmountOrdered ();
        $paymentMethod = null;

        switch ($order->getPayment ()->getMethod ())
        {
            case 'cashondelivery': { $paymentMethod = self::NFE_PAYMENT_TYPE_MONEY; break; }
        }

        $nfce->setPaymentAmount ($paymentAmount)
            ->setPaymentMethod ($paymentMethod)
            ->save ()
        ;

    __return:

        $nfce->setFantasyName ($this->_fantasyName)
            ->setCompanyName ($this->_companyName)
        ;

        $result = array (
            'entity_id'       => intval ($nfce->getId ()),
            'order_id'        => intval ($nfce->getOrderId ()),
            'store_id'        => intval ($nfce->getStoreId ()),
            'customer_id'     => intval ($nfce->getCustomerId ()),
            'environment_id'  => intval ($nfce->getEnvironmentId ()),
            'version_id'      => strval ($nfce->getVersionId ()),
            'model_id'        => intval ($nfce->getModelId ()),
            'series_id'       => intval ($nfce->getSeriesId ()),
            'batch_id'        => intval ($nfce->getBatchId ()),
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
            'operation'       => strval ($nfce->getOperation ()),
            'code'            => strval ($nfce->getCode ()),
            'fantasy_name'    => strval ($nfce->getFantasyName ()),
            'company_taxvat'  => strval ($nfce->getCompanyTaxvat ()),
            'company_name'    => strval ($nfce->getCompanyName ()),
            'company_ie'      => strval ($nfce->getCompanyIe ()),
            'customer_taxvat' => strval ($nfce->getCustomerTaxvat ()),
            'customer_email'  => strval ($nfce->getCustomerEmail ()),
            'customer_ie'     => strval ($nfce->getCustomerIe ()),
            'payment_method'  => strval ($nfce->getPaymentMethod ()),
            'payment_amount'  => floatval ($nfce->getPaymentAmount ()),
            'created_at'      => strval ($nfce->getCreatedAt ()),
            // Toluca_PDV
            'is_pdv'          => boolval ($nfce->getIsPdv ()),
            'pdv_cashier_id'  => intval ($nfce->getPdvCashierId ()),
            'pdv_operator_id' => intval ($nfce->getPdvOperatorId ()),
            'pdv_customer_id' => intval ($nfce->getPdvCustomerId ()),
            'pdv_history_id'  => intval ($nfce->getPdvHistoryId ()),
            'pdv_sequence_id' => intval ($nfce->getPdvSequenceId ()),
            'pdv_table_id'    => intval ($nfce->getPdvTableId ()),
        );

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

