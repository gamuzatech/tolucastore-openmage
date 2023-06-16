<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Customer address api
 */
class Gamuza_Basic_Model_Customer_Address_Api
    extends Mage_Customer_Model_Address_Api
{
    public function items($customerId)
    {
        $customer = Mage::getModel('customer/customer')->load($customerId);

        if (!$customer->getId())
        {
            $this->_fault('customer_not_exists');
        }

        $result = [];

        foreach ($customer->getAddresses() as $address)
        {
            $data = $address->toArray();

            $row  = [];

            foreach ($this->_mapAttributes as $attributeAlias => $attributeCode)
            {
                $row[$attributeAlias] = $data[$attributeCode] ?? null;
            }

            foreach ($this->getAllowedAttributes($address) as $attributeCode => $attribute)
            {
                if (isset($data[$attributeCode]))
                {
                    $row[$attributeCode] = $data[$attributeCode];
                }
            }

            $row['region_code'] = $address->getRegionCode();

            $row['is_default_billing'] = $customer->getDefaultBilling() == $address->getId();
            $row['is_default_shipping'] = $customer->getDefaultShipping() == $address->getId();

            $result[] = $row;
        }

        return $result;
    }
}

