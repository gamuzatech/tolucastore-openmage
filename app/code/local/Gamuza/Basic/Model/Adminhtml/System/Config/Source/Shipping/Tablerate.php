<?php
/**
 * @package     Gamuza_Basic
 * @copyright   Copyright (c) 2022 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 */

class Gamuza_Basic_Model_Adminhtml_System_Config_Source_Shipping_Tablerate
    extends Mage_Adminhtml_Model_System_Config_Source_Shipping_Tablerate
{
    public function toArray ()
    {
        $result = array ();

        foreach ($this->toOptionArray () as $option)
        {
            $result [$option ['value']] = $option ['label'];
        }

        return $result;
    }
}

