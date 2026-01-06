<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2025 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * One page checkout processing model
 */
class Gamuza_Basic_Model_Checkout_Type_Onepage extends Mage_Checkout_Model_Type_Onepage
{
    /**
     * Save billing address information to quote
     * This method is called by One Page Checkout JS (AJAX) while saving the billing information.
     *
     * @param array $data
     * @param int $customerAddressId
     * @return array|true
     * @throws Mage_Core_Exception
     */
    public function saveBilling($data, $customerAddressId)
    {
        if (!array_key_exists('email', $data) && Mage::getStoreConfigFlag(Gamuza_Basic_Model_Customer_Customer::XML_PATH_GENERATE_HUMAN_FRIENDLY_EMAIL))
        {
            $customerPrefix = Gamuza_Basic_Helper_Data::STORE_DEFAULT_EMAIL_PREFIX;
            $customerCode   = hash('md5', uniqid(rand(), true));
            $customerDomain = Mage::getStoreConfig(Mage_Customer_Model_Customer::XML_PATH_DEFAULT_EMAIL_DOMAIN);
            $customerEmail  = sprintf('%s+%s@%s', $customerPrefix, $customerCode, $customerDomain);

            $data['email'] = $customerEmail;
        }

        return parent::saveBilling($data, $customerAddressId);
    }
}

