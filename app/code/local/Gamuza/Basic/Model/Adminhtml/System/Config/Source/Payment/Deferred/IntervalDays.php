<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Deferred_IntervalDays payment config value selection
 */
class Gamuza_Basic_Model_Adminhtml_System_Config_Source_Payment_Deferred_IntervalDays
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toArray ()
    {
        $result = array ();

        for ($i = 0; $i <= 120; $i += 15)
        {
            $result [$i] = Mage::helper ('basic')->__('%d Days', $i);
        }

        return $result;
    }

    public function toOptionArray ()
    {
        $result = array ();

        foreach ($this->toArray () as $id => $value)
        {
            $result [] = array ('value' => $id, 'label' => $value);
        }

        return $result;
    }
}

