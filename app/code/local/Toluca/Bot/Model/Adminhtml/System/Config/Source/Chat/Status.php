<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2020 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Chat_Status config value selection
 *
 */
class Toluca_Bot_Model_Adminhtml_System_Config_Source_Chat_Status
{
    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray ()
    {
        $result = array(
            Toluca_Bot_Helper_Data::STATUS_CATEGORY => Mage::helper ('bot')->__('Category'),
            Toluca_Bot_Helper_Data::STATUS_PRODUCT  => Mage::helper ('bot')->__('Product'),
            Toluca_Bot_Helper_Data::STATUS_OPTION   => Mage::helper ('bot')->__('Option'),
            Toluca_Bot_Helper_Data::STATUS_VALUE    => Mage::helper ('bot')->__('Value'),
            Toluca_Bot_Helper_Data::STATUS_BUNDLE   => Mage::helper ('bot')->__('Bundle'),
            Toluca_Bot_Helper_Data::STATUS_SELECTION => Mage::helper ('bot')->__('Selection'),
            Toluca_Bot_Helper_Data::STATUS_COMMENT  => Mage::helper ('bot')->__('Comment'),
            Toluca_Bot_Helper_Data::STATUS_CART     => Mage::helper ('bot')->__('Cart'),
            Toluca_Bot_Helper_Data::STATUS_ADDRESS  => Mage::helper ('bot')->__('Address'),
            Toluca_Bot_Helper_Data::STATUS_SHIPPING => Mage::helper ('bot')->__('Shipping'),
            Toluca_Bot_Helper_Data::STATUS_PAYMENT  => Mage::helper ('bot')->__('Payment'),
            Toluca_Bot_Helper_Data::STATUS_PAYMENT_CASH    => Mage::helper ('bot')->__('Payment Cash'),
            Toluca_Bot_Helper_Data::STATUS_PAYMENT_MACHINE => Mage::helper ('bot')->__('Payment Machine'),
            Toluca_Bot_Helper_Data::STATUS_PAYMENT_CRIPTO  => Mage::helper ('bot')->__('Payment Cripto'),
            Toluca_Bot_Helper_Data::STATUS_CHECKOUT => Mage::helper ('bot')->__('Checkout'),
            Toluca_Bot_Helper_Data::STATUS_ORDER    => Mage::helper ('bot')->__('Order'),
        );

        return $result;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray ()
    {
        $result = array ();

        foreach ($this->toArray () as $value => $label)
        {
            $result [] = array ('value' => $value, 'label' => $label);
        }

        return $result;
    }
}

