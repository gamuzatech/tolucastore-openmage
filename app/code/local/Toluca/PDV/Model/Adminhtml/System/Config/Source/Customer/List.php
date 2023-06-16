<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Model_Adminhtml_System_Config_Source_Customer_List
{
    public function toOptionArray ()
    {
        $result = array (
            0 => Mage::helper ('core')->__('-- Please Select --'),
        );

        $collection = Mage::getModel ('customer/customer')->getCollection ()
            ->addNameToSelect ()
        ;

        $collection->getSelect ()
            ->where ('is_active = 1')
        ;

        foreach ($collection as $customer)
        {
            $result [$customer->getId ()] = sprintf ('%s - %s', $customer->getId (), $customer->getName ());
        }

        return $result;
    }
}

