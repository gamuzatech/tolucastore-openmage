<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Notification_Status config value selection
 *
 */
class Toluca_Bot_Model_Adminhtml_System_Config_Source_Notification_Status
{
    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray ()
    {
        $result = array(
            Toluca_Bot_Helper_Data::ORDER_STATUS_PENDING   => Mage::helper ('bot')->__('Pending'),
            Toluca_Bot_Helper_Data::ORDER_STATUS_PREPARING => Mage::helper ('bot')->__('Preparing'),
            Toluca_Bot_Helper_Data::ORDER_STATUS_PAID      => Mage::helper ('bot')->__('Paid'),
            Toluca_Bot_Helper_Data::ORDER_STATUS_SHIPPED   => Mage::helper ('bot')->__('Shipped'),
            Toluca_Bot_Helper_Data::ORDER_STATUS_DELIVERED => Mage::helper ('bot')->__('Delivered'),
            Toluca_Bot_Helper_Data::ORDER_STATUS_REFUNDED  => Mage::helper ('bot')->__('Refunded'),
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

