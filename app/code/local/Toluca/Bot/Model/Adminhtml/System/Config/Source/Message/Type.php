<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Message_Type config value selection
 *
 */
class Toluca_Bot_Model_Adminhtml_System_Config_Source_Message_Type
{
    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray ()
    {
        $result = array(
            Toluca_Bot_Helper_Data::MESSAGE_TYPE_QUESTION => Mage::helper ('bot')->__('Question'),
            Toluca_Bot_Helper_Data::MESSAGE_TYPE_ANSWER   => Mage::helper ('bot')->__('Answer'),
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

