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
            $this->_fault ('nfce_already_exists');
        }

        if (Mage::helper ('core')->isModuleEnabled ('Toluca_PDV')
            && $order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_IS_PDV)
            && !$order->getCustomerId ())
        {
            $order->setCustomerId ($order->getData (Toluca_PDV_Helper_Data::ORDER_ATTRIBUTE_PDV_CUSTOMER_ID));
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
            ->setVersion (Mage::getStoreConfig (self::XML_PATH_BRAZIL_NFCE_VERSION))
            ->setModel (Mage::getStoreConfig (self::XML_PATH_BRAZIL_NFCE_MODEL))
            ->setSeries (Mage::getStoreConfig (self::XML_PATH_BRAZIL_NFCE_SERIES))
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
            ->setCrt (self::NFE_CRT_SIMPLE_NATIONAL)
            ->setOperation ($operationName)
            ->setCode (hexdec ($polynomial))
            ->setFantasyName ($this->_fantasyName)
            ->setCompanyTaxvat ($this->_companyTaxvat)
            ->setCompanyName ($this->_companyName)
            ->setCompanyIE ($this->_companyIE)
            ->setCustomerTaxvat ($order->getCustomerTaxvat ())
            ->setCustomerEmail ($order->getCustomerEmail ())
            ->setCustomerIe (self::NFE_CUSTOMER_IE_NONE)
            ->setCreatedAt (date ('c'))
        ;

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

        $result = array (
            'entity_id'       => intval ($nfce->getId ()),
            'order_id'        => intval ($nfce->getOrderId ()),
            'store_id'        => intval ($nfce->getStoreId ()),
            'customer_id'     => intval ($nfce->getCustomerId ()),
            'environment_id'  => intval ($nfce->getEnvironmentId ()),
            'version'         => strval ($nfce->getVersion ()),
            'model'           => intval ($nfce->getModel ()),
            'series'          => intval ($nfce->getSeries ()),
            'batch_id'        => intval ($nfce->getBatchId ()),
            'region_id'       => intval ($nfce->getRegionId ()),
            'city_id'         => intval ($nfce->getCityId ()),
            'number_id'       => strval ($nfce->getNumberId ()),
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
            'operation'       => strval ($nfce->getOperation ()),
            'code'            => strval ($nfce->getCode ()),
            'fantasy_name'    => strval ($nfce->getFantasyName ()),
            'company_taxvat'  => strval ($nfce->getCompanyTaxvat ()),
            'company_name'    => strval ($nfce->getCompanyName ()),
            'company_ie'      => strval ($nfce->getCompanyIE ()),
            'customer_taxvat' => strval ($nfce->getCustomerTaxvat ()),
            'customer_email'  => strval ($nfce->getCustomerEmail ()),
            'customer_ie'     => intval ($nfce->getCustomerIe ()),
            'payment_method'  => strval ($nfce->getPaymentMethod ()),
            'payment_amount'  => floatval ($nfce->getPaymentAmount ()),
            'created_at'      => strval ($nfce->getCreatedAt ()),
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

