<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Customer entity resource model
 */
class Gamuza_Basic_Model_Customer_Resource_Customer
    extends Mage_Customer_Model_Resource_Customer
{
    /**
     * Check customer scope, email and confirmation key before saving
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return $this
     * @throws Mage_Core_Exception
     */
    protected function _beforeSave(Varien_Object $customer)
    {
        $collection = Mage::getModel('customer/customer')->getCollection()
            ->addAttributeToFilter('cellphone', $customer->getCellphone())
        ;

        if ($customer->getSharingConfig()->isWebsiteScope())
        {
            $collection->addAttributeToFilter('website_id', array('eq' => $customer->getWebsiteId()));
        }

        if ($customer->getId())
        {
            $collection->addAttributeToFilter('entity_id', array('neq' => $customer->getId()));
        }

        if ($collection->getSize() > 0)
        {
            throw Mage::exception(
                'Mage_Customer',
                Mage::helper('basic')->__('This customer cellphone already exists.'),
                Gamuza_Basic_Model_Customer_Customer::EXCEPTION_CELLPHONE_EXISTS
            );
        }

        if (!$customer->getEmail() && Mage::getStoreConfigFlag(Gamuza_Basic_Model_Customer_Customer::XML_PATH_GENERATE_HUMAN_FRIENDLY_EMAIL))
        {
            $customerCode   = hash('md5', uniqid(rand(), true));
            $customerDomain = Mage::getStoreConfig(Mage_Customer_Model_Customer::XML_PATH_DEFAULT_EMAIL_DOMAIN);
            $customerEmail  = sprintf('%s@%s', $customerCode, $customerDomain);

            $customer->setEmail($customerEmail);
        }

        return parent::_beforeSave($customer);
    }
}

