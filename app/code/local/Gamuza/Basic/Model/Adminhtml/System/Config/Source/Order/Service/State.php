<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2023 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Order_Service_State config value selection
 */
class Gamuza_Basic_Model_Adminhtml_System_Config_Source_Order_Service_State
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Basic_Model_Order_Service::STATE_OPEN,       'label' => Mage::helper ('basic')->__('Open')),
            array ('value' => Gamuza_Basic_Model_Order_Service::STATE_CLOSED,     'label' => Mage::helper ('basic')->__('Closed')),
            array ('value' => Gamuza_Basic_Model_Order_Service::STATE_PROCESSING, 'label' => Mage::helper ('basic')->__('Processing')),
            array ('value' => Gamuza_Basic_Model_Order_Service::STATE_CANCELED,   'label' => Mage::helper ('basic')->__('Canceled')),
            array ('value' => Gamuza_Basic_Model_Order_Service::STATE_REFUNDED,   'label' => Mage::helper ('basic')->__('Refunded')),
        );

        return $result;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $result = array(
            Gamuza_Basic_Model_Order_Service::STATE_OPEN       => Mage::helper ('basic')->__('Open'),
            Gamuza_Basic_Model_Order_Service::STATE_CLOSED     => Mage::helper ('basic')->__('Closed'),
            Gamuza_Basic_Model_Order_Service::STATE_PROCESSING => Mage::helper ('basic')->__('Processing'),
            Gamuza_Basic_Model_Order_Service::STATE_CANCELED   => Mage::helper ('basic')->__('Canceled'),
            Gamuza_Basic_Model_Order_Service::STATE_REFUNDED   => Mage::helper ('basic')->__('Refunded'),
        );

        return $result;
    }
}

