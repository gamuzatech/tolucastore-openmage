<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Customer address entity resource model
 */
class Gamuza_Basic_Model_Customer_Resource_Address
    extends Mage_Customer_Model_Resource_Address
{
    protected function _afterSave (Varien_Object $address)
    {
        $result = parent::_afterSave($address);

        if ($address->getId () && $address->getIsDefaultBilling ())
        {
            $customer = Mage::getModel ('customer/customer')
                ->load($address->getCustomerId ())
                ->setCellphone (preg_replace ('[\D]', null, $address->getCellphone ()))
                ->save ()
            ;
        }

        return $result;
    }
}

