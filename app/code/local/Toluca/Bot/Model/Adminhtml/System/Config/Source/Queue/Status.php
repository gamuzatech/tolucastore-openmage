<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Queue_Status config value selection
 *
 */
class Toluca_Bot_Model_Adminhtml_System_Config_Source_Queue_Status
{
    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray ()
    {
        $result = array(
            Toluca_Bot_Helper_Data::QUEUE_STATUS_PENDING  => Mage::helper ('bot')->__('Pending'),
            Toluca_Bot_Helper_Data::QUEUE_STATUS_SENDING  => Mage::helper ('bot')->__('Sending'),
            Toluca_Bot_Helper_Data::QUEUE_STATUS_FINISHED => Mage::helper ('bot')->__('Finished'),
            Toluca_Bot_Helper_Data::QUEUE_STATUS_CANCELED => Mage::helper ('bot')->__('Canceled'),
            Toluca_Bot_Helper_Data::QUEUE_STATUS_STOPPED  => Mage::helper ('bot')->__('Stopped'),
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

