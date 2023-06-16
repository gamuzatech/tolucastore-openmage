<?php
/**
 * @package     Gamuza_PagCripto
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Payment_Status config value selection
 */
class Gamuza_PagCripto_Model_Adminhtml_System_Config_Source_Payment_Status
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = array(
            array ('value' => Gamuza_PagCripto_Helper_Data::API_PAYMENT_STATUS_WAITING_FOR_PAYMENT, 'label' => Mage::helper ('pagcripto')->__('Waiting For Payment')),
            array ('value' => Gamuza_PagCripto_Helper_Data::API_PAYMENT_STATUS_ERROR,               'label' => Mage::helper ('pagcripto')->__('Error')),
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
            Gamuza_PagCripto_Helper_Data::API_PAYMENT_STATUS_WAITING_FOR_PAYMENT => Mage::helper ('pagcripto')->__('Waiting For Payment'),
            Gamuza_PagCripto_Helper_Data::API_PAYMENT_STATUS_ERROR               => Mage::helper ('pagcripto')->__('Error'),
        );

        return $result;
    }
}

