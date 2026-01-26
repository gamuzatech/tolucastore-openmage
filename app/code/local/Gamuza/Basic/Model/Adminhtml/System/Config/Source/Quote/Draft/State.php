<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Quote_Draft_State config value selection
 */
class Gamuza_Basic_Model_Adminhtml_System_Config_Source_Quote_Draft_State
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_Basic_Model_Quote_Draft::STATE_OPEN,       'label' => Mage::helper ('basic')->__('Open')),
            array ('value' => Gamuza_Basic_Model_Quote_Draft::STATE_CLOSED,     'label' => Mage::helper ('basic')->__('Closed')),
            array ('value' => Gamuza_Basic_Model_Quote_Draft::STATE_PROCESSING, 'label' => Mage::helper ('basic')->__('Processing')),
            array ('value' => Gamuza_Basic_Model_Quote_Draft::STATE_CANCELED,   'label' => Mage::helper ('basic')->__('Canceled')),
            array ('value' => Gamuza_Basic_Model_Quote_Draft::STATE_REFUNDED,   'label' => Mage::helper ('basic')->__('Refunded')),
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
            Gamuza_Basic_Model_Quote_Draft::STATE_OPEN       => Mage::helper ('basic')->__('Open'),
            Gamuza_Basic_Model_Quote_Draft::STATE_CLOSED     => Mage::helper ('basic')->__('Closed'),
            Gamuza_Basic_Model_Quote_Draft::STATE_PROCESSING => Mage::helper ('basic')->__('Processing'),
            Gamuza_Basic_Model_Quote_Draft::STATE_CANCELED   => Mage::helper ('basic')->__('Canceled'),
            Gamuza_Basic_Model_Quote_Draft::STATE_REFUNDED   => Mage::helper ('basic')->__('Refunded'),
        );

        return $result;
    }
}

