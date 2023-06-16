<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

trait Gamuza_Mobile_Trait_Api_Resource
{
    /**
     * Retrieves quote by customer identifier and store code
     *
     * @param int $quoteId
     * @param string|int $store
     * @return Mage_Sales_Model_Quote
     */
    protected function _getCustomerQuote($customerCode, $store = null, $createNewQuote = false)
    {
        $storeId = Mage::getStoreConfig (Gamuza_Mobile_Helper_Data::XML_PATH_API_MOBILE_STORE_VIEW, $store);

        $customerPrefix = Mage::getStoreConfig (Gamuza_Mobile_Helper_Data::XML_PATH_DEFAULT_EMAIL_PREFIX);
        $customerDomain = Mage::getStoreConfig (Mage_Customer_Model_Customer::XML_PATH_DEFAULT_EMAIL_DOMAIN);
        $customerEmail  = sprintf ('%s+%s@%s', $customerPrefix, $customerCode, $customerDomain);

        /** @var $quote Mage_Sales_Model_Quote */
        $quote = Mage::getModel("sales/quote")
            ->setStoreId($storeId)
            ->load($customerEmail, 'customer_email')
        ;

        if ((!$quote || !$quote->getId()) && $createNewQuote)
        {
            $quote = $this->_createNewQuote ($customerCode, $customerEmail, $storeId);
        }

        if (!$quote || !$quote->getId())
        {
            $this->_fault('quote_not_exists');
        }

        $quote->afterLoad();

        return $quote;
    }

    /**
     * Create new quote for shopping cart
     *
     * @param int|string $store
     * @return int
     */
    protected function _createNewQuote ($customerCode, $customerEmail, $storeId)
    {
        $remoteIp = Mage::helper('core/http')->getRemoteAddr(false);

        $firstName = Mage::getStoreConfig ('general/store_information/name', $storeId);
        $lastName  = Mage::getStoreConfig ('general/store_information/name', $storeId);

        $storeCode = Mage::getStoreConfig ('general/store_information/code', $storeId);

        try
        {
            /*@var $quote Mage_Sales_Model_Quote*/
            $quote = Mage::getModel('sales/quote')
                ->setStoreId($storeId)
                ->setIsActive(true)
                ->setIsMultiShipping(false)
                ->setRemoteIp($remoteIp)
                ->setCustomerFirstname ($firstName)
                ->setCustomerLastname ($lastName)
                ->setCustomerEmail ($customerEmail)
                ->setCustomerTaxvat (Gamuza_Mobile_Helper_Data::DEFAULT_CUSTOMER_TAXVAT)
                ->save()
            ;

            $customerData = array(
                'mode'      => Mage_Checkout_Model_Type_Onepage::METHOD_GUEST,
                'firstname' => $firstName,
                'lastname'  => $lastName,
                'email'     => $customerEmail,
                'taxvat'    => Gamuza_Mobile_Helper_Data::DEFAULT_CUSTOMER_TAXVAT,
            );

            Mage::getModel ('checkout/cart_customer_api')->set ($quote->getId (), $customerData, $storeId);

            $quote->setData (Gamuza_Mobile_Helper_Data::ORDER_ATTRIBUTE_IS_APP, true)
                ->setData (Gamuza_Mobile_Helper_Data::ORDER_ATTRIBUTE_CUSTOMER_INFO_CODE, $customerCode)
                ->setData (Gamuza_Mobile_Helper_Data::ORDER_ATTRIBUTE_STORE_INFO_CODE, $storeCode)
                ->setCustomerGroupId (0)
                ->setCustomerIsGuest (1)
                ->save()
            ;

            $shippingPostcode = preg_replace ('[\D]', null, Mage::getStoreConfig ('shipping/origin/postcode', $storeId));

            Mage::getModel ('checkout/cart_customer_api')->setAddresses ($quote->getId (), array(
                array(
                    'mode'       => 'billing',
                    'firstname'  => $firstName,
                    'lastname'   => $lastName,
                    'street'     => array(
                        Mage::getStoreConfig ('shipping/origin/street_line1', $storeId),
                        Mage::getStoreConfig ('shipping/origin/street_line2', $storeId),
                        Mage::getStoreConfig ('shipping/origin/street_line3', $storeId),
                        Mage::getStoreConfig ('shipping/origin/street_line4', $storeId),
                    ),
                    'city'       => Mage::getStoreConfig ('shipping/origin/city',       $storeId),
                    'region'     => Mage::getStoreConfig ('shipping/origin/region_id',  $storeId),
                    'country_id' => Mage::getStoreConfig ('shipping/origin/country_id', $storeId),
                    'postcode'   => $shippingPostcode,
                    'cellphone'  => Mage::getStoreConfig ('general/store_information/phone', $storeId),
                    'use_for_shipping' => 1,
                )
            ), $storeId);
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_fault('create_quote_fault', $e->getMessage());
        }

        return $quote;
    }

    protected function _getPaymentMethodAvailableCcTypes ($method)
    {
        $methodCcTypes = explode(',', $method->getConfigData('cctypes'));

        $result = array ();

        $ccTypes = Mage::getSingleton('mobile/payment_config')->getCcTypes();

        foreach ($ccTypes as $code => $title)
        {
            if (in_array($code, $methodCcTypes))
            {
                $result[$code] = $title;
            }
        }

        if (Mage::helper ('core')->isModuleEnabled ('Gamuza_PagCripto'))
        {
            $ccTypes = Mage::getSingleton('pagcripto/payment_config')->getCcTypes();

            foreach ($ccTypes as $code => $title)
            {
                if (in_array($code, $methodCcTypes))
                {
                    $result[$code] = $title;
                }
            }
        }

        if (empty($result))
        {
            return null;
        }

        return $result;
    }

    public function _getCcAvailableInstallments ($quote, $method)
    {
        return null;
    }

    public function _getRemoteSessionId ($quote, $method)
    {
        $result = null;

        if (Mage::helper ('core')->isModuleEnabled ('RicardoMartins_PagSeguro')
            && !strcmp ($method->getCode (), RicardoMartins_PagSeguro_Model_Payment_Cc::CODE))
        {
            $result = Mage::helper ('ricardomartins_pagseguro')->getSessionId ();
        }

        return $result;
    }
}

