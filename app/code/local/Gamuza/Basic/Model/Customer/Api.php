<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Customer api
 */
class Gamuza_Basic_Model_Customer_Api extends Mage_Customer_Model_Api_Resource
{
    protected $_mapAttributes = array(
        'customer_id' => 'entity_id'
    );

    public function items($filters = null, $order = null, $limit = null)
    {
        $collection = Mage::getModel('customer/customer')->getCollection()->addAttributeToSelect('*');

        $collection->getSelect()
            ->joinLeft(
                array ('cg' => Mage::getSingleton('core/resource')->getTablename('customer/customer_group')),
                'e.group_id = cg.customer_group_id',
                array (
                    'group_code' => 'cg.customer_group_code',
                    'group_name' => 'cg.name',
                    'group_is_system' => 'cg.is_system',
                    'tax_class_id' => 'cg.tax_class_id',
                )
            )
        ;

	if (!empty($filters['full_name']['full_name']['like']))
	{
	    $value = $filters['full_name']['full_name']['like'];

	    $collection->addAttributeToSelect('firstname', 'left')
	        ->addAttributeToSelect('lastname', 'left')
                ->getSelect()->where(
		    "CONCAT(at_firstname.value, ' ', at_lastname.value) LIKE ?",
		    $value
	        )
	    ;

	    unset($filters['full_name']);
	}

        /** @var Mage_Api_Helper_Data $apiHelper */
        $apiHelper = Mage::helper('api');

        $filters = $apiHelper->parseFilters($filters, $this->_mapAttributes);

        try
        {
            foreach ($filters as $field => $value)
            {
                $collection->addFieldToFilter($field, $value);
            }

            if (!empty ($order)) $collection->getSelect ()->order ($order);
            if (!empty ($limit)) $collection->getSelect ()->limit ($limit);
        }
        catch (Mage_Core_Exception $e)
        {
            $this->_fault('filters_invalid', $e->getMessage());
        }

        $result = [];

        /** @var Mage_Customer_Model_Customer $customer */
        foreach ($collection as $customer)
        {
            $data = $customer->toArray();

            $row  = array();

            foreach ($this->_mapAttributes as $attributeAlias => $attributeCode)
            {
                $row[$attributeAlias] = $data[$attributeCode] ?? null;
            }

            foreach ($this->getAllowedAttributes($customer) as $attributeCode => $attribute)
            {
                if (isset($data[$attributeCode]))
                {
                    $row[$attributeCode] = $data[$attributeCode];
                }
            }

            $row['group_code'] = $data['group_code'];
            $row['group_name'] = $data['group_name'];
            $row['group_is_system'] = boolval ($data['group_is_system']);
            $row['tax_class_id'] = $data['tax_class_id'];

            unset($row['password_hash']);

            $result[] = $row;
        }

        return $result;
    }

    public function welcome ($customerId)
    {
        if (empty ($customerId))
        {
            $this->_fault ('customer_not_specified');
        }

        $customer = Mage::getModel ('basic/customer_customer')->load ($customerId);

        if (!$customer || !$customer->getId ())
        {
            $this->_fault ('customer_not_exists');
        }

        $customer->sendNewAccountEmail ();

        return true;
    }

    public function subscribe ($customerId)
    {
        if (empty ($customerId))
        {
            $this->_fault ('customer_not_specified');
        }

        $customer = Mage::getModel ('basic/customer_customer')->load ($customerId);

        if (!$customer || !$customer->getId ())
        {
            $this->_fault ('customer_not_exists');
        }

        $customer->setStoreId (Mage_Core_Model_App::DISTRO_STORE_ID)
            ->setIsSubscribed (true)
            ->save ()
        ;

        Mage::getModel ('newsletter/subscriber')->subscribeCustomer ($customer);

        return true;
    }
}

