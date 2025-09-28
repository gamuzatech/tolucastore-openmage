<?php
/**
 * @package     Toluca_Bot
 * @copyright   Copyright (c) 2021 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Bot_Type config value selection
 *
 */
class Toluca_Bot_Model_Adminhtml_System_Config_Source_Bot_Type
{
    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray ()
    {
        $result = array(
            Toluca_Bot_Helper_Data::BOT_TYPE_ADMIN  => Mage::helper ('bot')->__('Admin'),
            Toluca_Bot_Helper_Data::BOT_TYPE_URA    => Mage::helper ('bot')->__('U.R.A.'),
            Toluca_Bot_Helper_Data::BOT_TYPE_OPENAI => Mage::helper ('bot')->__('OpenAI'),
            Toluca_Bot_Helper_Data::BOT_TYPE_GEMINI => Mage::helper ('bot')->__('Gemini'),
            Toluca_Bot_Helper_Data::BOT_TYPE_CLAUDE => Mage::helper ('bot')->__('Claude'),
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

