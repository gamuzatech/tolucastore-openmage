<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2026 Gamuza Technologies (https://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

/**
 * Used in creating options for Deferred_InstallmentsQty payment config value selection
 */
class Gamuza_Basic_Model_Adminhtml_System_Config_Source_Payment_Deferred_InstallmentsQty
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toArray ()
    {
        $result = array ();

        for ($i = 1; $i <= 120; $i ++)
        {
            $result [$i] = Mage::helper ('basic')->__('%d Installments', $i);
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

