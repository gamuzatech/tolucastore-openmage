<?php
/**
 * @package     Toluca_PDV
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Toluca_PDV_Model_Adminhtml_System_Config_Source_Operator_List
{
    public function toOptionArray ()
    {
        $result = array (
            0 => Mage::helper ('core')->__('-- Please Select --'),
        );

        $collection = Mage::getModel ('pdv/operator')->getCollection ();

        foreach ($collection as $operator)
        {
            $result [$operator->getId ()] = sprintf ('%s - %s', $operator->getId (), $operator->getName ());
        }

        return $result;
    }
}

