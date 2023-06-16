<?php
/**
 * @package     Gamuza_Mobile
 * @copyright   Copyright (c) 2017 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Shopping cart api for customer data
 */
class Gamuza_Mobile_Model_Cart_Customer_Api extends Mage_Checkout_Model_Cart_Customer_Api
{
    use Gamuza_Mobile_Trait_Api_Resource;

    protected $_ignoredAttributeCodes = array(
        'customer_id', 'website_id', 'store_id', 'group_id',
    );

    /**
     * @param  int $quoteId
     * @param  array of array|object $customerAddressData
     * @param  int|string $store
     * @return int
     */
    public function _setAddresses($code = null, $customerAddressData = null, $store = null)
    {
        if (empty ($code))
        {
            $this->_fault ('customer_code_not_specified');
        }

        $quote = $this->_getCustomerQuote($code, $store);

        $customerAddressData = $this->_prepareCustomerAddressData($customerAddressData);

        if (is_null($customerAddressData))
        {
            $this->_fault('customer_address_data_empty');
        }

        foreach ($customerAddressData as $addressItem)
        {
//            switch($addressItem['mode']) {
//            case self::ADDRESS_BILLING:
                /** @var $address Mage_Sales_Model_Quote_Address */
                $address = Mage::getModel("sales/quote_address");
//                break;
//            case self::ADDRESS_SHIPPING:
//                /** @var $address Mage_Sales_Model_Quote_Address */
//                $address = Mage::getModel("sales/quote_address");
//                break;
//            }
            $addressMode = $addressItem['mode'];

            unset($addressItem['mode']);

            if (!empty($addressItem['entity_id']))
            {
                $customerAddress = $this->_getCustomerAddress($addressItem['entity_id']);

                if ($customerAddress->getCustomerId() != $quote->getCustomerId())
                {
                    $this->_fault('address_not_belong_customer');
                }

                $address->importCustomerAddress($customerAddress);
            }
            else
            {
                $address->setData($addressItem);
            }

            $address->implodeStreetAddress();

            if (($validateRes = $address->validate())!==true)
            {
                $this->_fault('customer_address_invalid', implode(PHP_EOL, $validateRes));
            }

            if (!$address->getEmail() && $quote->getCustomerEmail())
            {
                $address->setEmail($quote->getCustomerEmail());
            }

            switch($addressMode)
            {
                case self::ADDRESS_BILLING:
                {
                    if (!$quote->isVirtual())
                    {
                        $usingCase = isset($addressItem['use_for_shipping']) ? (int)$addressItem['use_for_shipping'] : 0;

                        switch($usingCase)
                        {
                            case 0:
                            {
                                $shippingAddress = $quote->getShippingAddress();
                                $shippingAddress->setSameAsBilling(0);

                                break;
                            }
                            case 1:
                            {
                                $billingAddress = clone $address;
                                $billingAddress->unsAddressId()->unsAddressType();

                                $shippingAddress = $quote->getShippingAddress();
                                $shippingMethod = $shippingAddress->getShippingMethod();

                                $shippingAddress->addData($billingAddress->getData())
                                    ->setSameAsBilling(1)
                                    ->setShippingMethod($shippingMethod)
                                    ->setCollectShippingRates(true)
                                ;

                                break;
                            }
                        }
                    }

                    $quote->setCustomerFirstname($address->getFirstname());
                    $quote->setCustomerLastname($address->getLastname());

                    $quote->setBillingAddress($address);

                    break;
                }
                case self::ADDRESS_SHIPPING:
                {
                    $address->setCollectShippingRates(true)
                        ->setSameAsBilling(0)
                    ;

                    $quote->setShippingAddress($address);

                    break;
                }
            }

        }

        try
        {
            $quote->collectTotals()->save();
        }
        catch (Exception $e)
        {
            $this->_fault('address_is_not_set', $e->getMessage());
        }

        return true;
    }
}

