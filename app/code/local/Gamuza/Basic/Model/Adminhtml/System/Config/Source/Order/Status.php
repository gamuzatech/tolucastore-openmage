<?php
/*
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Order Statuses source model
 */
class Gamuza_Basic_Model_Adminhtml_System_Config_Source_Order_Status
{
    // set null to enable all possible
    protected $_stateStatuses = null;

    public function toOptionArray()
    {
        if ($this->_stateStatuses)
        {
            $statuses = Mage::getSingleton('sales/order_config')->getStateStatuses($this->_stateStatuses);
        }
        else
        {
            $statuses = Mage::getSingleton('sales/order_config')->getStatuses();
        }

        $options = array();

        $options [] = array(
           'value' => '',
           'label' => Mage::helper('adminhtml')->__('-- Please Select --')
        );

        foreach ($statuses as $code => $label)
        {
            $options [] = array(
               'value' => $code,
               'label' => $label
            );
        }

        return $options;
    }

    public function toArray ()
    {
        $result = array ();

        foreach ($this->toOptionArray () as $option)
        {
            $result [$option ['value']] = $option ['label'];
        }

        return $result;
    }
}

